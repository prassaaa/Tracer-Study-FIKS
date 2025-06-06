<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendidikanLanjut extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_profile_id',
        'institusi',
        'jenjang',
        'program_studi',
        'tahun_masuk',
        'tahun_lulus',
        'sedang_berjalan',
        'keterangan',
    ];

    protected $casts = [
        'sedang_berjalan' => 'boolean',
    ];

    public function alumniProfile()
    {
        return $this->belongsTo(AlumniProfile::class);
    }
}