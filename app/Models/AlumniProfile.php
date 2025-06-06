<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'program_studi_id',
        'nim',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'tahun_masuk',
        'tahun_lulus',
        'no_telepon',
        'alamat',
        'foto',
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan program studi
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    // Relasi dengan riwayat pekerjaan
    public function riwayatPekerjaan()
    {
        return $this->hasMany(RiwayatPekerjaan::class);
    }
    
    // Relasi dengan pendidikan terakhir
    public function jawabanSurvei()
    {
    return $this->hasMany(JawabanSurvei::class);
    }

    // Relasi dengan hasil clustering
    public function pendidikanLanjut()
    {
        return $this->hasMany(PendidikanLanjut::class);
    }

    // Relasi dengan hasil clustering
    public function clusteringAlumni()
    {
        return $this->hasMany(ClusteringAlumni::class);
    }
}