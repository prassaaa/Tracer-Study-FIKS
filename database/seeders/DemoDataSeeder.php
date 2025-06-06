<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AlumniProfile;
use App\Models\RiwayatPekerjaan;
use App\Models\PendidikanLanjut;
use App\Models\Survei;
use App\Models\PertanyaanSurvei;
use App\Models\JawabanSurvei;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Buat 50 user alumni
        $alumni = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => 'alumni' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'alumni',
            ]);
            
            $alumni[] = $user;
        }
        
        // Buat profil alumni
        foreach ($alumni as $user) {
            $profile = AlumniProfile::factory()->create([
                'user_id' => $user->id,
            ]);
            
            // Buat 1-3 riwayat pekerjaan untuk setiap alumni
            $jumlahPekerjaan = fake()->numberBetween(1, 3);
            
            $pekerjaan = RiwayatPekerjaan::factory()->count($jumlahPekerjaan)->create([
                'alumni_profile_id' => $profile->id,
                'pekerjaan_saat_ini' => false,
            ]);
            
            // Tetapkan satu pekerjaan sebagai pekerjaan saat ini
            $pekerjaan->last()->update(['pekerjaan_saat_ini' => true]);
            
            // Buat pendidikan lanjut untuk 30% alumni
            if (fake()->boolean(30)) {
                PendidikanLanjut::factory()->create([
                    'alumni_profile_id' => $profile->id,
                ]);
            }
        }
        
        // Buat survei
        $survei1 = Survei::create([
            'judul' => 'Survei Kepuasan Alumni 2025',
            'deskripsi' => 'Survei untuk mengetahui tingkat kepuasan alumni terhadap layanan kampus',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-12-31',
            'aktif' => true,
        ]);
        
        $survei2 = Survei::create([
            'judul' => 'Survei Karir Alumni 2025',
            'deskripsi' => 'Survei untuk mengetahui perkembangan karir alumni',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-12-31',
            'aktif' => true,
        ]);
        
        // Buat pertanyaan untuk survei 1
        $pertanyaanSurvei1 = [
            [
                'survei_id' => $survei1->id,
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap kualitas pengajaran di kampus?',
                'tipe' => 'radio',
                'pilihan' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
                'urutan' => 1,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei1->id,
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap fasilitas kampus?',
                'tipe' => 'radio',
                'pilihan' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
                'urutan' => 2,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei1->id,
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap pelayanan administrasi?',
                'tipe' => 'radio',
                'pilihan' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'],
                'urutan' => 3,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei1->id,
                'pertanyaan' => 'Saran untuk perbaikan kampus',
                'tipe' => 'text',
                'pilihan' => null,
                'urutan' => 4,
                'wajib' => false,
            ],
        ];
        
        foreach ($pertanyaanSurvei1 as $pertanyaan) {
            PertanyaanSurvei::create($pertanyaan);
        }
        
        // Buat pertanyaan untuk survei 2
        $pertanyaanSurvei2 = [
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Berapa lama waktu tunggu Anda untuk mendapatkan pekerjaan pertama?',
                'tipe' => 'radio',
                'pilihan' => ['< 3 bulan', '3-6 bulan', '6-12 bulan', '> 12 bulan'],
                'urutan' => 1,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Apakah pekerjaan Anda sesuai dengan bidang studi?',
                'tipe' => 'radio',
                'pilihan' => ['Sangat Sesuai', 'Sesuai', 'Cukup Sesuai', 'Kurang Sesuai', 'Tidak Sesuai'],
                'urutan' => 2,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Berapa gaji/penghasilan Anda saat ini?',
                'tipe' => 'radio',
                'pilihan' => ['< 3 juta', '3-5 juta', '5-10 juta', '10-20 juta', '> 20 juta'],
                'urutan' => 3,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Apa kendala terbesar dalam karir Anda?',
                'tipe' => 'checkbox',
                'pilihan' => ['Skill', 'Pengalaman', 'Jaringan', 'Sertifikasi', 'Bahasa Asing', 'Lainnya'],
                'urutan' => 4,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Apakah Anda berminat melanjutkan pendidikan?',
                'tipe' => 'radio',
                'pilihan' => ['Ya', 'Tidak', 'Sudah/Sedang Melanjutkan'],
                'urutan' => 5,
                'wajib' => true,
            ],
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Jika Ya, jenjang pendidikan apa yang ingin Anda lanjutkan?',
                'tipe' => 'select',
                'pilihan' => ['S2', 'S3', 'Profesi', 'Spesialis', 'Lainnya'],
                'urutan' => 6,
                'wajib' => false,
            ],
            [
                'survei_id' => $survei2->id,
                'pertanyaan' => 'Rencana karir Anda dalam 5 tahun ke depan',
                'tipe' => 'text',
                'pilihan' => null,
                'urutan' => 7,
                'wajib' => false,
            ],
        ];
        
        foreach ($pertanyaanSurvei2 as $pertanyaan) {
            PertanyaanSurvei::create($pertanyaan);
        }
        
        // Buat jawaban survei untuk beberapa alumni (sampel)
        $alumniSample = AlumniProfile::inRandomOrder()->take(20)->get();
        
        foreach ($alumniSample as $profile) {
            // Jawaban untuk survei 1
            $pertanyaanSurvei1 = PertanyaanSurvei::where('survei_id', $survei1->id)->get();
            
            foreach ($pertanyaanSurvei1 as $pertanyaan) {
                $jawaban = '';
                
                if ($pertanyaan->tipe === 'radio') {
                    // Ambil jawaban acak dari pilihan
                    $jawaban = fake()->randomElement($pertanyaan->pilihan);
                } elseif ($pertanyaan->tipe === 'checkbox') {
                    // Ambil 1-3 jawaban acak dari pilihan
                    $jumlahPilihan = min(count($pertanyaan->pilihan), fake()->numberBetween(1, 3));
                    $pilihan = fake()->randomElements($pertanyaan->pilihan, $jumlahPilihan);
                    $jawaban = implode(', ', $pilihan);
                } elseif ($pertanyaan->tipe === 'select') {
                    // Ambil jawaban acak dari pilihan
                    $jawaban = fake()->randomElement($pertanyaan->pilihan);
                } elseif ($pertanyaan->tipe === 'text') {
                    // Buat jawaban teks acak
                    $jawaban = fake()->paragraph(fake()->numberBetween(1, 3));
                }
                
                JawabanSurvei::create([
                    'alumni_profile_id' => $profile->id,
                    'pertanyaan_survei_id' => $pertanyaan->id,
                    'jawaban' => $jawaban,
                ]);
            }
            
            // Jawaban untuk survei 2 (untuk sebagian alumni saja)
            if (fake()->boolean(70)) {
                $pertanyaanSurvei2 = PertanyaanSurvei::where('survei_id', $survei2->id)->get();
                
                foreach ($pertanyaanSurvei2 as $pertanyaan) {
                    $jawaban = '';
                    
                    if ($pertanyaan->tipe === 'radio') {
                        // Ambil jawaban acak dari pilihan
                        $jawaban = fake()->randomElement($pertanyaan->pilihan);
                    } elseif ($pertanyaan->tipe === 'checkbox') {
                        // Ambil 1-3 jawaban acak dari pilihan
                        $jumlahPilihan = min(count($pertanyaan->pilihan), fake()->numberBetween(1, 3));
                        $pilihan = fake()->randomElements($pertanyaan->pilihan, $jumlahPilihan);
                        $jawaban = implode(', ', $pilihan);
                    } elseif ($pertanyaan->tipe === 'select') {
                        // Ambil jawaban acak dari pilihan
                        $jawaban = fake()->randomElement($pertanyaan->pilihan);
                    } elseif ($pertanyaan->tipe === 'text') {
                        // Buat jawaban teks acak
                        $jawaban = fake()->paragraph(fake()->numberBetween(1, 3));
                    }
                    
                    // Jika pertanyaan opsional, mungkin tidak dijawab
                    if (!$pertanyaan->wajib && fake()->boolean(30)) {
                        continue;
                    }
                    
                    JawabanSurvei::create([
                        'alumni_profile_id' => $profile->id,
                        'pertanyaan_survei_id' => $pertanyaan->id,
                        'jawaban' => $jawaban,
                    ]);
                }
            }
        }
    }
}