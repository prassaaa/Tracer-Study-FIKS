<?php

namespace App\Services;

class ClusteringService
{
    /**
     * Melakukan proses Single Linkage Clustering
     *
     * @param array $data Data yang akan dicluster
     * @param int $k Jumlah cluster yang diinginkan
     * @return array Hasil clustering
     */
    public function singleLinkageClustering(array $data, int $k): array
    {
        // 1. Inisialisasi: setiap data point adalah cluster tersendiri
        $clusters = [];
        $clusterAssignments = [];
        $distances = [];
        
        foreach ($data as $i => $point) {
            $clusters[$i] = [$point];
            $clusterAssignments[$point['id']] = $i;
        }
        
        // 2. Hitung matriks jarak antar cluster
        $distanceMatrix = [];
        for ($i = 0; $i < count($data); $i++) {
            for ($j = $i + 1; $j < count($data); $j++) {
                $distance = $this->calculateDistance($data[$i], $data[$j]);
                $distanceMatrix[$i][$j] = $distance;
                $distanceMatrix[$j][$i] = $distance;
            }
        }
        
        // 3. Merge cluster secara iteratif hingga jumlah cluster = k
        while (count($clusters) > $k) {
            // Cari pasangan cluster dengan jarak minimum
            $minDistance = PHP_FLOAT_MAX;
            $clusterPair = [-1, -1];
            
            foreach ($distanceMatrix as $i => $distances) {
                foreach ($distances as $j => $distance) {
                    if ($i != $j && $distance < $minDistance) {
                        $minDistance = $distance;
                        $clusterPair = [$i, $j];
                    }
                }
            }
            
            // Jika tidak ada pasangan valid yang ditemukan
            if ($clusterPair[0] == -1) {
                break;
            }
            
            // Gabungkan dua cluster terdekat
            $cluster1 = $clusterPair[0];
            $cluster2 = $clusterPair[1];
            
            // Gabungkan cluster2 ke cluster1
            $clusters[$cluster1] = array_merge($clusters[$cluster1], $clusters[$cluster2]);
            
            // Update cluster assignments
            foreach ($clusters[$cluster2] as $point) {
                $clusterAssignments[$point['id']] = $cluster1;
            }
            
            // Hapus cluster2
            unset($clusters[$cluster2]);
            
            // Update matriks jarak
            unset($distanceMatrix[$cluster2]);
            foreach ($distanceMatrix as &$row) {
                unset($row[$cluster2]);
            }
            
            // Update jarak antara cluster yang digabung dengan cluster lainnya (Single Linkage: jarak minimum)
            foreach ($clusters as $j => $clusterPoints) {
                if ($j != $cluster1) {
                    $minDist = PHP_FLOAT_MAX;
                    
                    // Cari jarak minimum antara points di cluster1 dan points di cluster j
                    foreach ($clusters[$cluster1] as $point1) {
                        foreach ($clusterPoints as $point2) {
                            $dist = $this->calculateDistance($point1, $point2);
                            if ($dist < $minDist) {
                                $minDist = $dist;
                            }
                        }
                    }
                    
                    $distanceMatrix[$cluster1][$j] = $minDist;
                    $distanceMatrix[$j][$cluster1] = $minDist;
                }
            }
        }
        
        // Reindex clusters
        $reindexedClusters = [];
        $reindexedAssignments = [];
        $i = 0;
        
        foreach ($clusters as $oldIndex => $clusterPoints) {
            $reindexedClusters[$i] = $clusterPoints;
            
            foreach ($clusterPoints as $point) {
                $reindexedAssignments[$point['id']] = $i;
                $distances[$point['id']] = 0; // Jarak ke centroid, diisi 0 sebagai placeholder
            }
            
            $i++;
        }
        
        return [
            'clusters' => $reindexedClusters,
            'cluster_assignments' => $reindexedAssignments,
            'distances' => $distances
        ];
    }
    
    /**
     * Menghitung jarak Euclidean antara dua titik
     *
     * @param array $point1 Titik pertama
     * @param array $point2 Titik kedua
     * @return float Jarak antara dua titik
     */
    public function calculateDistance(array $point1, array $point2): float
    {
        // Hitung jarak Euclidean antara dua titik
        $sum = 0;
        
        foreach ($point1['features'] as $feature => $value1) {
            if (isset($point2['features'][$feature])) {
                $value2 = $point2['features'][$feature];
                $sum += pow($value1 - $value2, 2);
            }
        }
        
        return sqrt($sum);
    }
    
/**
 * Prapemrosesan data alumni untuk clustering
 *
 * @param array|Illuminate\Database\Eloquent\Collection $alumni Data alumni
 * @param array $atribut Atribut yang akan digunakan
 * @return array Data yang sudah diproses untuk clustering
 */
public function preprocessData($alumni, array $atribut): array
{
    $dataForClustering = [];
    
    foreach ($alumni as $alumnus) {
        $dataPoint = [
            'id' => $alumnus->id,
            'features' => []
        ];
        
        // Ambil data berdasarkan atribut yang dipilih
        foreach ($atribut as $attr) {
            switch ($attr) {
                case 'gaji':
                    // Ambil gaji dari pekerjaan terkini
                    $riwayatTerkini = $alumnus->riwayatPekerjaan()
                        ->where('pekerjaan_saat_ini', true)
                        ->orWhere(function($query) {
                            $query->whereNull('tanggal_selesai')
                                  ->orderBy('tanggal_mulai', 'desc');
                        })
                        ->first();
                        
                    $dataPoint['features']['gaji'] = $riwayatTerkini ? $riwayatTerkini->gaji : 0;
                    break;
                    
                case 'bidang_pekerjaan':
                    // Kategorikan bidang pekerjaan
                    $riwayatTerkini = $alumnus->riwayatPekerjaan()
                        ->where('pekerjaan_saat_ini', true)
                        ->orWhere(function($query) {
                            $query->whereNull('tanggal_selesai')
                                  ->orderBy('tanggal_mulai', 'desc');
                        })
                        ->first();
                        
                    $bidangPekerjaan = $riwayatTerkini ? $riwayatTerkini->bidang_pekerjaan : '';
                    // Enkode bidang pekerjaan menjadi nilai numerik
                    $dataPoint['features']['bidang_pekerjaan'] = crc32($bidangPekerjaan) % 100;
                    break;
                    
                case 'waktu_tunggu':
                    // Hitung waktu tunggu (dalam bulan) antara lulus dan pekerjaan pertama
                    $firstJob = $alumnus->riwayatPekerjaan()->orderBy('tanggal_mulai', 'asc')->first();
                    
                    if ($firstJob && $alumnus->tahun_lulus) {
                        $tanggalLulus = \Carbon\Carbon::createFromDate($alumnus->tahun_lulus, 6, 30); // Asumsi lulus pada pertengahan tahun
                        $tanggalMulaiKerja = \Carbon\Carbon::parse($firstJob->tanggal_mulai);
                        $waktuTunggu = $tanggalLulus->diffInMonths($tanggalMulaiKerja);
                        $dataPoint['features']['waktu_tunggu'] = $waktuTunggu > 0 ? $waktuTunggu : 0;
                    } else {
                        $dataPoint['features']['waktu_tunggu'] = 0;
                    }
                    break;
                    
                case 'jenjang_pendidikan':
                    // Kode jenjang pendidikan: 0 = tidak lanjut, 1 = S2, 2 = S3, dll
                    $jenjangTertinggi = $alumnus->pendidikanLanjut()->orderBy('jenjang', 'desc')->first();
                    
                    if ($jenjangTertinggi) {
                        switch (strtoupper($jenjangTertinggi->jenjang)) {
                            case 'S2': $dataPoint['features']['jenjang_pendidikan'] = 1; break;
                            case 'S3': $dataPoint['features']['jenjang_pendidikan'] = 2; break;
                            default: $dataPoint['features']['jenjang_pendidikan'] = 0.5; break;
                        }
                    } else {
                        $dataPoint['features']['jenjang_pendidikan'] = 0;
                    }
                    break;
            }
        }
        
        $dataForClustering[] = $dataPoint;
    }
    return $dataForClustering;
    }
}