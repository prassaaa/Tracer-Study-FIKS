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
        Schema::create('riwayat_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_profile_id')->constrained()->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->string('posisi');
            $table->string('lokasi')->nullable();
            $table->string('bidang_pekerjaan');
            $table->integer('gaji')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('pekerjaan_saat_ini')->default(false);
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pekerjaans');
    }
};
