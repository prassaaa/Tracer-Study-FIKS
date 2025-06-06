<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clustering_alumnis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_clustering_id')->constrained()->onDelete('cascade');
            $table->foreignId('alumni_profile_id')->constrained()->onDelete('cascade');
            $table->integer('cluster_id');
            $table->double('jarak_ke_centroid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clustering_alumnis');
    }
};
