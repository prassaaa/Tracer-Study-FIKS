<?php

namespace Database\Factories;

use App\Models\RiwayatPekerjaan;
use App\Models\AlumniProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class RiwayatPekerjaanFactory extends Factory
{
    protected $model = RiwayatPekerjaan::class;

    public function definition()
    {
        $alumni = AlumniProfile::inRandomOrder()->first();
        $tanggalLulus = Carbon::createFromDate($alumni->tahun_lulus, 6, 30);
        $waktuTunggu = $this->faker->numberBetween(0, 24);
        $tanggalMulai = $tanggalLulus->copy()->addMonths($waktuTunggu);
        
        // 70% pekerjaan masih berlangsung, 30% sudah selesai
        $masihBerlangsung = $this->faker->boolean(70);
        $tanggalSelesai = $masihBerlangsung ? null : $tanggalMulai->copy()->addMonths($this->faker->numberBetween(6, 48));
        
        $bidangPekerjaan = $this->faker->randomElement([
            'Pendidikan', 'Kesehatan', 'Teknologi Informasi', 'Perbankan',
            'Pemerintahan', 'Swasta', 'BUMN', 'Wiraswasta', 'Konsultan'
        ]);
        
        // Tentukan range gaji berdasarkan bidang
        $gajiMin = 2500000; // 2.5 juta
        $gajiMax = 15000000; // 15 juta
        
        if ($bidangPekerjaan === 'Teknologi Informasi' || $bidangPekerjaan === 'Perbankan') {
            $gajiMin = 5000000; // 5 juta
        } elseif ($bidangPekerjaan === 'Kesehatan') {
            $gajiMin = 4000000; // 4 juta
        }
        
        return [
            'alumni_profile_id' => $alumni->id,
            'nama_perusahaan' => $this->faker->company(),
            'posisi' => $this->faker->jobTitle(),
            'lokasi' => $this->faker->city(),
            'bidang_pekerjaan' => $bidangPekerjaan,
            'gaji' => $this->faker->numberBetween($gajiMin, $gajiMax),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'pekerjaan_saat_ini' => $masihBerlangsung,
            'deskripsi_pekerjaan' => $this->faker->paragraph(),
        ];
    }
}