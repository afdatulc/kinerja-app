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
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained()->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('kegiatan_masters')->onDelete('cascade');
            $table->string('pegawai_nip');
            $table->tinyInteger('triwulan');
            $table->string('tahapan'); // Selection from available stages
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('uraian');
            $table->json('lampiran')->nullable();
            $table->timestamps();

            $table->foreign('pegawai_nip')->references('nip')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas');
    }
};
