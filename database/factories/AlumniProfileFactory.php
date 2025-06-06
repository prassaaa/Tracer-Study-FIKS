<?php

namespace Database\Factories;

use App\Models\AlumniProfile;
use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumniProfileFactory extends Factory
{
    protected $model = AlumniProfile::class;

    public function definition()
    {
        $tahunMasuk = $this->faker->numberBetween(2010, 2018);
        $tahunLulus = $tahunMasuk + $this->faker->numberBetween(3, 5);
        
        return [
            'program_studi_id' => ProgramStudi::inRandomOrder()->first()->id,
            'nim' => $this->faker->unique()->numerify('##########'),
            'nama_lengkap' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-35 years', '-18 years'),
            'tahun_masuk' => $tahunMasuk,
            'tahun_lulus' => $tahunLulus,
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
        ];
    }
}