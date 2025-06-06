<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'fakultas',
        'kode',
        'deskripsi',
    ];

    // Relasi dengan profil alumni
    public function alumniProfiles()
    {
        return $this->hasMany(AlumniProfile::class);
    }
}