<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_profile_id',
        'nama_perusahaan',
        'posisi',
        'lokasi',
        'bidang_pekerjaan',
        'gaji',
        'tanggal_mulai',
        'tanggal_selesai',
        'pekerjaan_saat_ini',
        'deskripsi_pekerjaan',
    ];

    // Relasi dengan profil alumni
    public function alumniProfile()
    {
        return $this->belongsTo(AlumniProfile::class);
    }
}