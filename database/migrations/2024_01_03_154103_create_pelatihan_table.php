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
        Schema::create('pelatihan', function (Blueprint $table)  {
            $table->increments('id');
            $table->string('kode')->unique();
            $table->string('nama');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('not started yet');
            $table->string('penyelenggara');
            $table->string('tempat');
            $table->string('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};
