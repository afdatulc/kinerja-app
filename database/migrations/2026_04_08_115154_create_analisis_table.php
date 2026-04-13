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
        Schema::create('analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('triwulan');
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->string('pic_tindak_lanjut')->nullable();
            $table->date('batas_waktu')->nullable();
            $table->string('link_bukti_kinerja')->nullable();
            $table->string('link_bukti_tindak_lanjut')->nullable();
            $table->string('file_bukti_kinerja')->nullable();
            $table->string('file_bukti_tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis');
    }
};
