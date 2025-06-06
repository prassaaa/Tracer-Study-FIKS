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
        Schema::create('jawaban_surveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('pertanyaan_survei_id')->constrained()->onDelete('cascade');
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_surveis');
    }
};
