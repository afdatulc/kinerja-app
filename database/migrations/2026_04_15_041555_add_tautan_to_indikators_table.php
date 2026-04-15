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
            $table->string('link_bukti_kinerja')->nullable()->after('dasar_hitung');
            $table->string('link_bukti_tindak_lanjut')->nullable()->after('link_bukti_kinerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikators', function (Blueprint $table) {
            $table->dropColumn(['link_bukti_kinerja', 'link_bukti_tindak_lanjut']);
        });
    }
};
