<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilClustering extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_proses',
        'deskripsi',
        'parameter',
        'jumlah_cluster',
        'hasil',
        'waktu_proses',
    ];

    protected $casts = [
        'parameter' => 'array',
        'hasil' => 'array',
        'waktu_proses' => 'datetime',
    ];

    public function clusteringAlumni()
    {
        return $this->hasMany(ClusteringAlumni::class);
    }
}