<?php

namespace App\Exports;

use App\Models\AlumniProfile;

class AlumniExport
{
    public static function getData()
    {
        $alumni = AlumniProfile::with(['user', 'programStudi', 'riwayatPekerjaan', 'pendidikanLanjut'])
            ->get();

        $data = [];

        // Header
        $data[] = [
            'NIM',
            'Nama Lengkap',
            'Email',
            'Program Studi',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Tahun Masuk',
            'Tahun Lulus',
            'No Telepon',
            'Alamat',
            'Status Pekerjaan',
            'Nama Perusahaan',
            'Posisi/Jabatan',
            'Bidang Pekerjaan',
            'Gaji',
            'Waktu Tunggu Kerja (Bulan)',
            'Pendidikan Lanjut',
            'Institusi Pendidikan',
            'Jenjang Pendidikan',
            'Bidang Studi',
            'Tahun Masuk Pendidikan',
            'Status Pendidikan'
        ];

        // Data rows
        foreach ($alumni as $alumnus) {
            // Ambil pekerjaan saat ini
            $pekerjaanSaatIni = $alumnus->riwayatPekerjaan()
                ->where('pekerjaan_saat_ini', true)
                ->first();

            // Ambil pendidikan lanjut yang sedang berjalan atau terakhir
            $pendidikanLanjut = $alumnus->pendidikanLanjut()
                ->orderBy('tahun_masuk', 'desc')
                ->first();

            $data[] = [
                $alumnus->nim,
                $alumnus->nama_lengkap,
                $alumnus->user->email ?? '',
                $alumnus->programStudi->nama ?? '',
                $alumnus->jenis_kelamin,
                $alumnus->tanggal_lahir ? \Carbon\Carbon::parse($alumnus->tanggal_lahir)->format('d/m/Y') : '',
                $alumnus->tahun_masuk,
                $alumnus->tahun_lulus,
                $alumnus->no_telepon,
                $alumnus->alamat,
                $pekerjaanSaatIni ? $pekerjaanSaatIni->status_pekerjaan : 'Belum Bekerja',
                $pekerjaanSaatIni ? $pekerjaanSaatIni->nama_perusahaan : '',
                $pekerjaanSaatIni ? $pekerjaanSaatIni->posisi_jabatan : '',
                $pekerjaanSaatIni ? $pekerjaanSaatIni->bidang_pekerjaan : '',
                $pekerjaanSaatIni && $pekerjaanSaatIni->gaji ? 'Rp ' . number_format($pekerjaanSaatIni->gaji, 0, ',', '.') : '',
                $pekerjaanSaatIni ? $pekerjaanSaatIni->waktu_tunggu_kerja : '',
                $pendidikanLanjut ? 'Ya' : 'Tidak',
                $pendidikanLanjut ? $pendidikanLanjut->nama_institusi : '',
                $pendidikanLanjut ? $pendidikanLanjut->jenjang_pendidikan : '',
                $pendidikanLanjut ? $pendidikanLanjut->bidang_studi : '',
                $pendidikanLanjut ? $pendidikanLanjut->tahun_masuk : '',
                $pendidikanLanjut ? $pendidikanLanjut->status_pendidikan : ''
            ];
        }

        return $data;
    }
}
