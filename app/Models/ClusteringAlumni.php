<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusteringAlumni extends Model
{
    use HasFactory;

    protected $fillable = [
        'hasil_clustering_id',
        'alumni_profile_id',
        'cluster_id',
        'jarak_ke_centroid',
    ];

    public function hasilClustering()
    {
        return $this->belongsTo(HasilClustering::class);
    }

    public function alumniProfile()
    {
        return $this->belongsTo(AlumniProfile::class);
    }
}