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
        Schema::table('nilai_test', function (Blueprint $table) {
            $table->dropForeign(['jawaban_id']); // Remove this line
            $table->dropColumn('jawaban_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_test', function (Blueprint $table) {
            $table->unsignedBigInteger('jawaban_id');
            $table->foreign('jawaban_id')->references('id')->on('jawaban_test')->onDelete('cascade');
        });
    }
};
