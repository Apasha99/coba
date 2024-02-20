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
        Schema::create('attempt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('test_id');
            $table->integer('totalnilai');
            $table->foreign('peserta_id')->references('id')->on('peserta')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('test')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt');
    }
};
