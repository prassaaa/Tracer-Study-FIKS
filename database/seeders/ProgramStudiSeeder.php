<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        $programStudis = [
            [
                'nama' => 'Kebidanan',
                'fakultas' => 'Fakultas Ilmu Kesehatan dan Sains',
                'kode' => 'KBD',
            ],
            [
                'nama' => 'Keperawatan',
                'fakultas' => 'Fakultas Ilmu Kesehatan dan Sains',
                'kode' => 'KPR',
            ],
            [
                'nama' => 'Peternakan',
                'fakultas' => 'Fakultas Ilmu Kesehatan dan Sains',
                'kode' => 'PTR',
            ],
            [
                'nama' => 'Pendidikan Biologi',
                'fakultas' => 'Fakultas Ilmu Kesehatan dan Sains',
                'kode' => 'PBI',
            ],
            [
                'nama' => 'Pendidikan Matematika',
                'fakultas' => 'Fakultas Ilmu Kesehatan dan Sains',
                'kode' => 'PMT',
            ],
            [
                'nama' => 'Pendidikan Jasmani Kesehatan dan Rekreasi',
                'fakultas' => 'Fakultas Ilmu Kesehatan dan Sains',
                'kode' => 'PJK',
            ],
        ];

        foreach ($programStudis as $prodi) {
            ProgramStudi::create($prodi);
        }
    }
}