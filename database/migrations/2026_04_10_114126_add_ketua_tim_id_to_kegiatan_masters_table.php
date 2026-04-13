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
        Schema::table('kegiatan_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('ketua_tim_id')->nullable()->after('nama_kegiatan');
            $table->foreign('ketua_tim_id')->references('id')->on('pegawais')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_masters', function (Blueprint $table) {
            //
        });
    }
};
