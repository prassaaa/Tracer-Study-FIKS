<?php

namespace Database\Factories;

use App\Models\PertanyaanSurvei;
use App\Models\Survei;
use Illuminate\Database\Eloquent\Factories\Factory;

class PertanyaanSurveiFactory extends Factory
{
    protected $model = PertanyaanSurvei::class;

    public function definition()
    {
        $tipe = $this->faker->randomElement(['text', 'radio', 'checkbox', 'select']);
        $pilihan = null;
        
        if ($tipe !== 'text') {
            $pilihan = [
                $this->faker->word(),
                $this->faker->word(),
                $this->faker->word(),
                $this->faker->word(),
                $this->faker->word(),
            ];
        }
        
        return [
            'survei_id' => Survei::inRandomOrder()->first()->id,
            'pertanyaan' => $this->faker->sentence() . '?',
            'tipe' => $tipe,
            'pilihan' => $pilihan,
            'urutan' => $this->faker->numberBetween(1, 20),
            'wajib' => $this->faker->boolean(70),
        ];
    }
}