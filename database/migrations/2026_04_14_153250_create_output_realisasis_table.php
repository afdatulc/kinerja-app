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
        Schema::create('output_realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('output_master_id')->constrained('output_masters')->onDelete('cascade');
            $table->tinyInteger('triwulan');
            $table->decimal('volume', 15, 2)->nullable();
            $table->decimal('progres', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('output_realisasis');
    }
};
