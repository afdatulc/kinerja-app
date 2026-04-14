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
        // Data Cleanup: Fill empty codes with a default pattern
        $indikators = \Illuminate\Support\Facades\DB::table('indikators')
            ->whereNull('kode')
            ->orWhere('kode', '')
            ->get();

        foreach ($indikators as $indikator) {
            \Illuminate\Support\Facades\DB::table('indikators')
                ->where('id', $indikator->id)
                ->update(['kode' => 'IND-' . str_pad($indikator->id, 3, '0', STR_PAD_LEFT)]);
        }

        Schema::table('indikators', function (Blueprint $table) {
            // Drop unique if exists (to be safe on retries)
            // But SQLite doesn't support dropping indexes easily without name.
            $table->unique('kode', 'indikators_kode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikators', function (Blueprint $table) {
            $table->dropUnique('indikators_kode_unique');
        });
    }
};
