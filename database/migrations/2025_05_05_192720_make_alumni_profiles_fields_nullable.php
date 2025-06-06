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
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('program_studi_id')->nullable()->change();
            $table->string('jenis_kelamin')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->integer('tahun_masuk')->nullable()->change();
            $table->integer('tahun_lulus')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('program_studi_id')->nullable(false)->change();
            $table->string('jenis_kelamin')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->integer('tahun_masuk')->nullable(false)->change();
            $table->integer('tahun_lulus')->nullable(false)->change();
        });
    }
};
