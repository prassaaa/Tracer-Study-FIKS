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
        Schema::create('pertanyaan_surveis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_id')->constrained()->onDelete('cascade');
            $table->string('pertanyaan');
            $table->string('tipe'); // text, radio, checkbox, select
            $table->text('pilihan')->nullable(); // JSON untuk pilihan jika tipe radio, checkbox, select
            $table->integer('urutan');
            $table->boolean('wajib')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_surveis');
    }
};
