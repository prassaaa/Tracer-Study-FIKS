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
        Schema::create('pendidikan_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_profile_id')->constrained()->onDelete('cascade');
            $table->string('institusi');
            $table->string('jenjang'); // S2, S3, dll
            $table->string('program_studi');
            $table->string('tahun_masuk');
            $table->string('tahun_lulus')->nullable();
            $table->boolean('sedang_berjalan')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikan_lanjuts');
    }
};
