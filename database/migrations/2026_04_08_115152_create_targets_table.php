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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indikator_id')->constrained()->onDelete('cascade');
            $table->decimal('target_tw1', 15, 2)->nullable();
            $table->decimal('target_tw2', 15, 2)->nullable();
            $table->decimal('target_tw3', 15, 2)->nullable();
            $table->decimal('target_tw4', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
