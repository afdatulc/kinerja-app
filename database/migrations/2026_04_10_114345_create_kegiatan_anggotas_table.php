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
        Schema::create('kegiatan_anggotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kegiatan_master_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->timestamps();

            $table->foreign('kegiatan_master_id')->references('id')->on('kegiatan_masters')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_anggotas');
    }
};
