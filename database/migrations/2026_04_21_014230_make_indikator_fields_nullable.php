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
        Schema::table('indikators', function (Blueprint $table) {
            $table->text('tujuan')->nullable()->change();
            $table->string('periode')->nullable()->change(); // Using string for flexibility
            $table->string('tipe')->nullable()->change();    // Using string for flexibility
            $table->string('satuan')->nullable()->change();
            $table->decimal('target_tahunan', 15, 2)->nullable()->change();
            $table->string('jenis_indikator')->change();     // Using string for flexibility
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikators', function (Blueprint $table) {
            $table->text('tujuan')->nullable(false)->change();
            $table->enum('periode', ['Triwulanan', 'Tahunan'])->nullable(false)->change();
            $table->enum('tipe', ['Persen', 'Non Persen'])->nullable(false)->change();
            $table->string('satuan')->nullable(false)->change();
            $table->decimal('target_tahunan', 15, 2)->nullable(false)->change();
            $table->enum('jenis_indikator', ['IKU', 'Proksi'])->nullable(false)->change();
        });
    }
};
