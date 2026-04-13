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
        Schema::create('output_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained()->onDelete('cascade');
            $table->string('nama_output');
            $table->enum('jenis_output', ['Laporan', 'Publikasi']);
            $table->enum('periode', ['Tahunan', 'Triwulanan', 'Bulanan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_masters');
    }
};
