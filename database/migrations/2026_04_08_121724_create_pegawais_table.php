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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('email_bps')->nullable()->unique();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->enum('status', ['PNS', 'PPPK', 'Outsourcing', 'Lainnya'])->default('PNS');
            $table->enum('seksi', ['Sosial', 'Produksi', 'Distribusi', 'Nerwilis', 'IPDS', 'Umum', 'Lainnya'])->default('Lainnya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
