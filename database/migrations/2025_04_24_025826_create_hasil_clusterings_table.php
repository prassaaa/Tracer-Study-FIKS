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
        Schema::create('hasil_clusterings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proses');
            $table->text('deskripsi')->nullable();
            $table->text('parameter'); // JSON parameter yang digunakan
            $table->integer('jumlah_cluster');
            $table->text('hasil'); // JSON hasil clustering
            $table->timestamp('waktu_proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_clusterings');
    }
};
