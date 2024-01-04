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
        Schema::create('peserta_pelatihan', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_id');
            $table->string('plt_kode');
            $table->foreign('peserta_id')->references('id')->on('peserta');
            $table->foreign('plt_kode')->references('kode')->on('pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_pelatihan');
    }
};
