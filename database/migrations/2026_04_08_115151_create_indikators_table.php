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
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->text('tujuan');
            $table->text('sasaran');
            $table->string('indikator_kinerja');
            $table->enum('jenis_indikator', ['IKU', 'Proksi']);
            $table->enum('periode', ['Triwulanan', 'Tahunan']);
            $table->enum('tipe', ['Persen', 'Non Persen']);
            $table->string('satuan');
            $table->decimal('target_tahunan', 15, 2);
            $table->integer('tahun')->default(2026);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikators');
    }
};
