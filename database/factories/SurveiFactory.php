<?php

namespace Database\Factories;

use App\Models\Survei;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SurveiFactory extends Factory
{
    protected $model = Survei::class;

    public function definition()
    {
        $tanggalMulai = Carbon::now()->subMonths($this->faker->numberBetween(0, 6));
        $tanggalSelesai = $tanggalMulai->copy()->addMonths($this->faker->numberBetween(3, 12));
        
        return [
            'judul' => $this->faker->sentence(),
            'deskripsi' => $this->faker->paragraph(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'aktif' => $this->faker->boolean(80),
        ];
    }
}