<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSurvei extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_profile_id',
        'pertanyaan_survei_id',
        'jawaban',
    ];

    public function alumniProfile()
    {
        return $this->belongsTo(AlumniProfile::class);
    }

    public function pertanyaanSurvei()
    {
        return $this->belongsTo(PertanyaanSurvei::class);
    }
}