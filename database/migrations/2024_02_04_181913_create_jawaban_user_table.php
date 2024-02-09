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
        Schema::create('jawaban_user_singkat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('soal_id');
            $table->string('jawaban');
            $table->foreign('peserta_id')->references('id')->on('peserta')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('test')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soal_test')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_user');
    }
};
