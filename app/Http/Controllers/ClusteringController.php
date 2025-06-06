<?php

namespace App\Http\Controllers;

use App\Models\AlumniProfile;
use App\Models\HasilClustering;
use App\Models\ClusteringAlumni;
use App\Models\RiwayatPekerjaan;
use App\Models\ProgramStudi;
use App\Services\ClusteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClusteringController extends Controller
{
    protected $clusteringService;

    public function __construct(ClusteringService $clusteringService)
    {
        $this->clusteringService = $clusteringService;
    }

    public function index()
    {
        $hasilClusterings = HasilClustering::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.clustering.index', compact('hasilClusterings'));
    }

    public function create()
    {
        $programStudis = ProgramStudi::all();
        return view('admin.clustering.create', compact('programStudis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proses' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah_cluster' => 'required|integer|min:2|max:10',
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'tahun_lulus_awal' => 'nullable|digits:4',
            'tahun_lulus_akhir' => 'nullable|digits:4',
            'atribut' => 'required|array',
            'atribut.*' => 'string|in:gaji,bidang_pekerjaan,waktu_tunggu,jenjang_pendidikan',
        ]);

        // Mulai proses clustering
        $hasilClustering = $this->prosesClusteringData($request);

        if ($hasilClustering) {
            return redirect()->route('admin.clustering.show', $hasilClustering->id)
                ->with('success', 'Proses clustering berhasil dilakukan.');
        } else {
            return back()->with('error', 'Proses clustering gagal. Pastikan data alumni tersedia.');
        }
    }

    private function prosesClusteringData($request)
    {
        // Ambil data alumni berdasarkan filter
        $query = AlumniProfile::with(['riwayatPekerjaan', 'pendidikanLanjut']);

        if ($request->program_studi_id) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        if ($request->tahun_lulus_awal) {
            $query->where('tahun_lulus', '>=', $request->tahun_lulus_awal);
        }

        if ($request->tahun_lulus_akhir) {
            $query->where('tahun_lulus', '<=', $request->tahun_lulus_akhir);
        }

        $alumni = $query->get();

        // Jika tidak ada data alumni yang memenuhi kriteria
        if ($alumni->isEmpty()) {
            return null;
        }

        // Preprocessing data menggunakan service
        $dataForClustering = $this->clusteringService->preprocessData($alumni, $request->atribut);

        // Jika data untuk clustering kosong
        if (empty($dataForClustering)) {
            return null;
        }

        // Proses clustering menggunakan service
        $hasil = $this->clusteringService->singleLinkageClustering($dataForClustering, $request->jumlah_cluster);

        // Jika hasil clustering kosong
        if (empty($hasil)) {
            return null;
        }

        // Buat record hasil clustering
        $hasilClustering = HasilClustering::create([
            'nama_proses' => $request->nama_proses,
            'deskripsi' => $request->deskripsi,
            'parameter' => [
                'program_studi_id' => $request->program_studi_id,
                'tahun_lulus_awal' => $request->tahun_lulus_awal,
                'tahun_lulus_akhir' => $request->tahun_lulus_akhir,
                'atribut' => $request->atribut,
            ],
            'jumlah_cluster' => $request->jumlah_cluster,
            'hasil' => $hasil['clusters'],
            'waktu_proses' => now(),
        ]);

        // Simpan hasil clustering per alumni
        foreach ($hasil['cluster_assignments'] as $alumniId => $clusterId) {
            ClusteringAlumni::create([
                'hasil_clustering_id' => $hasilClustering->id,
                'alumni_profile_id' => $alumniId,
                'cluster_id' => $clusterId,
                'jarak_ke_centroid' => $hasil['distances'][$alumniId] ?? null,
            ]);
        }

        return $hasilClustering;
    }

    public function show($id)
    {
        $hasilClustering = HasilClustering::with(['clusteringAlumni.alumniProfile.programStudi'])->findOrFail($id);

        // Kelompokkan alumni berdasarkan cluster
        $clusterGroups = $hasilClustering->clusteringAlumni->groupBy('cluster_id');

        return view('admin.clustering.show', compact('hasilClustering', 'clusterGroups'));
    }

    public function destroy($id)
    {
        $hasilClustering = HasilClustering::findOrFail($id);
        $hasilClustering->delete();

        return redirect()->route('admin.clustering.index')
            ->with('success', 'Hasil clustering berhasil dihapus.');
    }

    public function dashboard()
    {
        // Ambil hasil clustering terbaru
        $latestClustering = HasilClustering::with(['clusteringAlumni.alumniProfile'])->latest()->first();

        if (!$latestClustering) {
            return view('admin.clustering.dashboard')->with('error', 'Belum ada data clustering.');
        }

        // Kelompokkan alumni berdasarkan cluster
        $clusterGroups = $latestClustering->clusteringAlumni->groupBy('cluster_id');

        // Hitung statistik per cluster
        $clusterStats = [];

        foreach ($clusterGroups as $clusterId => $members) {
            $profiles = $members->pluck('alumniProfile');

            // Statistik gaji
            $gajiAvg = 0;
            $gajiCount = 0;

            foreach ($profiles as $profile) {
                $pekerjaan = $profile->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                if ($pekerjaan && $pekerjaan->gaji) {
                    $gajiAvg += $pekerjaan->gaji;
                    $gajiCount++;
                }
            }

            if ($gajiCount > 0) {
                $gajiAvg = $gajiAvg / $gajiCount;
            }

            // Hitung distribusi bidang pekerjaan
            $bidangPekerjaan = [];

            foreach ($profiles as $profile) {
                $pekerjaan = $profile->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                if ($pekerjaan) {
                    $bidang = $pekerjaan->bidang_pekerjaan;
                    if (!isset($bidangPekerjaan[$bidang])) {
                        $bidangPekerjaan[$bidang] = 0;
                    }
                    $bidangPekerjaan[$bidang]++;
                }
            }

            arsort($bidangPekerjaan);

            // Hitung statistik waktu tunggu rata-rata
            $waktuTungguAvg = 0;
            $waktuTungguCount = 0;

            foreach ($profiles as $profile) {
                $firstJob = $profile->riwayatPekerjaan()->orderBy('tanggal_mulai', 'asc')->first();
                if ($firstJob && $profile->tahun_lulus) {
                    $tanggalLulus = Carbon::createFromDate($profile->tahun_lulus, 6, 30);
                    $tanggalMulaiKerja = Carbon::parse($firstJob->tanggal_mulai);
                    $waktuTunggu = $tanggalLulus->diffInMonths($tanggalMulaiKerja);
                    if ($waktuTunggu > 0) {
                        $waktuTungguAvg += $waktuTunggu;
                        $waktuTungguCount++;
                    }
                }
            }

            if ($waktuTungguCount > 0) {
                $waktuTungguAvg = $waktuTungguAvg / $waktuTungguCount;
            }

            // Hitung distribusi jenjang pendidikan
            $jenjangPendidikan = [
                'Tidak Lanjut' => 0,
                'S2' => 0,
                'S3' => 0,
                'Lainnya' => 0
            ];

            foreach ($profiles as $profile) {
                $pendidikan = $profile->pendidikanLanjut()->orderBy('jenjang', 'desc')->first();
                if ($pendidikan) {
                    $jenjang = strtoupper($pendidikan->jenjang);
                    if ($jenjang === 'S2') {
                        $jenjangPendidikan['S2']++;
                    } elseif ($jenjang === 'S3') {
                        $jenjangPendidikan['S3']++;
                    } else {
                        $jenjangPendidikan['Lainnya']++;
                    }
                } else {
                    $jenjangPendidikan['Tidak Lanjut']++;
                }
            }

            // Distribusi program studi
            $programStudi = [];
            foreach ($profiles as $profile) {
                $prodiNama = $profile->programStudi->nama ?? 'Tidak Diketahui';
                if (!isset($programStudi[$prodiNama])) {
                    $programStudi[$prodiNama] = 0;
                }
                $programStudi[$prodiNama]++;
            }

            arsort($programStudi);

            $clusterStats[$clusterId] = [
                'count' => $members->count(),
                'gaji_rata_rata' => $gajiAvg,
                'waktu_tunggu_rata_rata' => $waktuTungguAvg,
                'bidang_pekerjaan' => $bidangPekerjaan,
                'jenjang_pendidikan' => $jenjangPendidikan,
                'program_studi' => $programStudi
            ];
        }

        return view('admin.clustering.dashboard', compact('latestClustering', 'clusterGroups', 'clusterStats'));
    }

    public function export($id)
    {
        $hasilClustering = HasilClustering::with(['clusteringAlumni.alumniProfile.programStudi',
                                                'clusteringAlumni.alumniProfile.riwayatPekerjaan',
                                                'clusteringAlumni.alumniProfile.pendidikanLanjut'])
                                           ->findOrFail($id);

        // Kelompokkan alumni berdasarkan cluster
        $clusterGroups = $hasilClustering->clusteringAlumni->groupBy('cluster_id');

        // Siapkan data untuk diekspor
        $data = [];
        $headers = ['Cluster', 'Nama Alumni', 'NIM', 'Program Studi', 'Tahun Lulus', 'Pekerjaan Terakhir', 'Posisi', 'Gaji'];

        foreach ($clusterGroups as $clusterId => $members) {
            foreach ($members as $member) {
                $alumni = $member->alumniProfile;
                $pekerjaan = $alumni->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();

                $row = [
                    'cluster' => $clusterId + 1,
                    'nama' => $alumni->nama_lengkap,
                    'nim' => $alumni->nim,
                    'prodi' => $alumni->programStudi->nama ?? '-',
                    'tahun_lulus' => $alumni->tahun_lulus,
                    'perusahaan' => $pekerjaan ? $pekerjaan->nama_perusahaan : '-',
                    'posisi' => $pekerjaan ? $pekerjaan->posisi : '-',
                    'gaji' => $pekerjaan ? 'Rp ' . number_format($pekerjaan->gaji, 0, ',', '.') : '-'
                ];

                $data[] = $row;
            }
        }

        // Cetak statistik per cluster
        $stats = [];
        foreach ($clusterGroups as $clusterId => $members) {
            $profiles = $members->pluck('alumniProfile');

            // Statistik gaji
            $gajiAvg = 0;
            $gajiCount = 0;

            foreach ($profiles as $profile) {
                $pekerjaan = $profile->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                if ($pekerjaan && $pekerjaan->gaji) {
                    $gajiAvg += $pekerjaan->gaji;
                    $gajiCount++;
                }
            }

            if ($gajiCount > 0) {
                $gajiAvg = $gajiAvg / $gajiCount;
            }

            $stats[] = [
                'cluster' => $clusterId + 1,
                'jumlah_anggota' => $members->count(),
                'gaji_rata_rata' => 'Rp ' . number_format($gajiAvg, 0, ',', '.')
            ];
        }

        return view('admin.clustering.export', compact('hasilClustering', 'data', 'headers', 'stats'));
    }

    public function analisis($id)
    {
        $hasilClustering = HasilClustering::with(['clusteringAlumni.alumniProfile.programStudi'])->findOrFail($id);

        // Kelompokkan alumni berdasarkan cluster
        $clusterGroups = $hasilClustering->clusteringAlumni->groupBy('cluster_id');

        // Periksa apakah data clustering ada
        if ($clusterGroups->isEmpty()) {
            return redirect()->route('admin.clustering.index')
                ->with('error', 'Data clustering tidak ditemukan atau kosong.');
        }

        // Hitung statistik per cluster
        $clusterStats = [];
        $chartData = [
            'labels' => [],
            'distribution' => [],
            'gaji' => [],
            'waktuTunggu' => [],
            'bidangLabels' => [],
            'bidangData' => []
        ];

        // Kumpulkan semua bidang pekerjaan unik dari semua cluster
        $allBidangPekerjaan = [];

        foreach ($clusterGroups as $clusterId => $members) {
            $profiles = $members->pluck('alumniProfile');
            $clusterLabel = 'Cluster ' . ($clusterId + 1);
            $chartData['labels'][] = $clusterLabel;
            $chartData['distribution'][] = (int) $members->count();

            // Statistik gaji dengan proteksi
            $gajiTotal = 0;
            $gajiCount = 0;

            foreach ($profiles as $profile) {
                $pekerjaan = $profile->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                if ($pekerjaan && $pekerjaan->gaji && $pekerjaan->gaji > 0) {
                    $gajiTotal += (float) $pekerjaan->gaji;
                    $gajiCount++;
                }
            }

            $gajiAvg = $gajiCount > 0 ? $gajiTotal / $gajiCount : 0;
            $chartData['gaji'][] = round($gajiAvg / 1000000, 2); // Konversi ke jutaan

            // Hitung distribusi bidang pekerjaan
            $bidangPekerjaan = [];

            foreach ($profiles as $profile) {
                $pekerjaan = $profile->riwayatPekerjaan()->where('pekerjaan_saat_ini', true)->first();
                if ($pekerjaan && !empty($pekerjaan->bidang_pekerjaan)) {
                    $bidang = trim($pekerjaan->bidang_pekerjaan);
                    $bidangPekerjaan[$bidang] = ($bidangPekerjaan[$bidang] ?? 0) + 1;

                    // Tambahkan ke collection semua bidang
                    if (!in_array($bidang, $allBidangPekerjaan)) {
                        $allBidangPekerjaan[] = $bidang;
                    }
                }
            }

            // Urutkan bidang pekerjaan berdasarkan jumlah (descending)
            arsort($bidangPekerjaan);

            // Waktu tunggu dengan proteksi
            $waktuTungguTotal = 0;
            $waktuTungguCount = 0;

            foreach ($profiles as $profile) {
                if (!$profile->tahun_lulus) continue;

                $firstJob = $profile->riwayatPekerjaan()->orderBy('tanggal_mulai', 'asc')->first();
                if ($firstJob && $firstJob->tanggal_mulai) {
                    try {
                        $tanggalLulus = Carbon::createFromDate($profile->tahun_lulus, 6, 30);
                        $tanggalMulaiKerja = Carbon::parse($firstJob->tanggal_mulai);
                        $waktuTunggu = $tanggalLulus->diffInMonths($tanggalMulaiKerja);

                        if ($waktuTunggu >= 0 && $waktuTunggu <= 60) { // Filter data yang masuk akal
                            $waktuTungguTotal += $waktuTunggu;
                            $waktuTungguCount++;
                        }
                    } catch (Exception $e) {
                        // Skip data tanggal yang tidak valid
                        continue;
                    }
                }
            }

            $waktuTungguAvg = $waktuTungguCount > 0 ? $waktuTungguTotal / $waktuTungguCount : 0;
            $chartData['waktuTunggu'][] = round($waktuTungguAvg, 1);

            // Simpan data lengkap untuk view
            $clusterStats[$clusterId] = [
                'count' => (int) $members->count(),
                'gaji_rata_rata' => $gajiAvg,
                'waktu_tunggu_rata_rata' => $waktuTungguAvg,
                'bidang_pekerjaan' => $bidangPekerjaan
            ];
        }

        // Siapkan data untuk chart bidang pekerjaan
        if (!empty($allBidangPekerjaan)) {
            $chartData['bidangLabels'] = $allBidangPekerjaan;

            // Buat dataset untuk setiap cluster
            $colors = [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(201, 203, 207, 0.7)',
                'rgba(255, 192, 203, 0.7)',
                'rgba(144, 238, 144, 0.7)',
                'rgba(255, 165, 0, 0.7)'
            ];

            foreach ($clusterGroups as $clusterId => $members) {
                $bidangData = [];
                $bidangPekerjaanCluster = $clusterStats[$clusterId]['bidang_pekerjaan'];

                // Isi data untuk setiap bidang
                foreach ($allBidangPekerjaan as $bidang) {
                    $bidangData[] = $bidangPekerjaanCluster[$bidang] ?? 0;
                }

                $chartData['bidangData'][] = [
                    'label' => 'Cluster ' . ($clusterId + 1),
                    'data' => $bidangData,
                    'backgroundColor' => $colors[$clusterId] ?? 'rgba(128, 128, 128, 0.7)',
                    'borderColor' => str_replace('0.7', '1', $colors[$clusterId] ?? 'rgba(128, 128, 128, 1)'),
                    'borderWidth' => 2
                ];
            }
        }

        // Pastikan semua data numerik
        $chartData['labels'] = array_values($chartData['labels']);
        $chartData['distribution'] = array_values($chartData['distribution']);
        $chartData['gaji'] = array_values($chartData['gaji']);
        $chartData['waktuTunggu'] = array_values($chartData['waktuTunggu']);

        // Konversi chart data ke JSON yang aman
        $chartDataJson = json_encode($chartData, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);

        // Debugging - hapus setelah selesai
        \Log::info('Chart Data:', $chartData);

        return view('admin.clustering.analisis', compact('hasilClustering', 'clusterGroups', 'clusterStats', 'chartDataJson'));
    }
}
