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
        Schema::table('analisis', function (Blueprint $table) {
            $table->string('pegawai_nip')->nullable()->after('indikator_id');
            $table->enum('severity', ['Low', 'Medium', 'High'])->default('Low')->after('batas_waktu');
            
            $table->foreign('pegawai_nip')->references('nip')->on('pegawais')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analisis', function (Blueprint $table) {
            $table->dropForeign(['pegawai_nip']);
            $table->dropColumn(['pegawai_nip', 'severity']);
        });
    }
};
