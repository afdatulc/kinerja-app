<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('indikators')->where('tipe', 'Persen')->update(['tipe' => '%']);
        DB::table('indikators')->where('tipe', 'Non Persen')->update(['tipe' => 'Non %']);
        // Also update if satuan was mistakenly used for these values
        DB::table('indikators')->where('satuan', 'Persen')->update(['satuan' => '%']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('indikators')->where('tipe', '%')->update(['tipe' => 'Persen']);
        DB::table('indikators')->where('tipe', 'Non %')->update(['tipe' => 'Non Persen']);
        DB::table('indikators')->where('satuan', '%')->update(['satuan' => 'Persen']);
    }
};
