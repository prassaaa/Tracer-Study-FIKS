<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanSurvei extends Model
{
    use HasFactory;

    protected $fillable = [
        'survei_id',
        'pertanyaan',
        'tipe',
        'pilihan',
        'urutan',
        'wajib',
    ];

    protected $casts = [
        'pilihan' => 'array',
        'wajib' => 'boolean',
    ];

    public function survei()
    {
        return $this->belongsTo(Survei::class);
    }

    public function jawabanSurvei()
    {
        return $this->hasMany(JawabanSurvei::class);
    }
}