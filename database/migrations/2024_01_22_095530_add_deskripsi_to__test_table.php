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
        Schema::table('Test', function (Blueprint $table) {
            $table->string('deskripsi')->nullable();
            $table->string('acak_soal');
            $table->string('acak_jawaban');
            $table->string('tampil_hasil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Test', function (Blueprint $table) {
            //
        });
    }
};
