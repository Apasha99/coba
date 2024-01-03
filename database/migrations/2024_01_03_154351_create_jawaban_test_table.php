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
        Schema::create('jawaban_test', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('soal_id');
            $table->string('title');
            $table->integer('urutan')->nullable();
            $table->boolean('status')->default(false);  // false refer to not correct answers
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
        Schema::dropIfExists('jawaban_test');
    }
};
