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
            $table->text('dasar_hitung')->nullable()->after('target_tahunan');
        });

        Schema::table('analisis', function (Blueprint $table) {
            $table->text('narasi_analisis')->nullable()->after('triwulan');
            $table->text('penjelasan_lainnya')->nullable()->after('rencana_tindak_lanjut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikators', function (Blueprint $table) {
            $table->dropColumn('dasar_hitung');
        });

        Schema::table('analisis', function (Blueprint $table) {
            $table->dropColumn(['narasi_analisis', 'penjelasan_lainnya']);
        });
    }
};
