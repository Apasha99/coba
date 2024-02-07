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
        Schema::create('instruktur_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instruktur_id');
            $table->string('plt_kode');
            $table->foreign('instruktur_id')->references('id')->on('instruktur');
            $table->foreign('plt_kode')->references('kode')->on('pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruktur__pelatihans');
    }
};
