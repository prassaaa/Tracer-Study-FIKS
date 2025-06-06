<?php

namespace Database\Factories;

use App\Models\PendidikanLanjut;
use App\Models\AlumniProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendidikanLanjutFactory extends Factory
{
    protected $model = PendidikanLanjut::class;

    public function definition()
    {
        $alumni = AlumniProfile::inRandomOrder()->first();
        $tahunMasuk = $alumni->tahun_lulus + $this->faker->numberBetween(0, 3);
        $sedangBerjalan = $this->faker->boolean(40);
        $tahunLulus = $sedangBerjalan ? null : $tahunMasuk + $this->faker->numberBetween(1, 3);
        
        $jenjang = $this->faker->randomElement(['S2', 'S3', 'Profesi']);
        
        $institusi = $this->faker->randomElement([
            'Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada',
            'Institut Pertanian Bogor', 'Universitas Airlangga', 'Universitas Brawijaya',
            'Universitas Diponegoro', 'Universitas Padjadjaran', 'Universitas Hasanuddin'
        ]);
        
        return [
            'alumni_profile_id' => $alumni->id,
            'institusi' => $institusi,
            'jenjang' => $jenjang,
            'program_studi' => $this->faker->randomElement([
                'Ilmu Komputer', 'Kedokteran', 'Ekonomi', 'Hukum', 'Manajemen',
                'Teknik Sipil', 'Teknik Elektro', 'Kesehatan Masyarakat', 'Keperawatan',
                'Farmasi', 'Akuntansi', 'Psikologi', 'Pendidikan', 'Biologi'
            ]),
            'tahun_masuk' => $tahunMasuk,
            'tahun_lulus' => $tahunLulus,
            'sedang_berjalan' => $sedangBerjalan,
            'keterangan' => $this->faker->sentence(),
        ];
    }
}