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
        Schema::table('peserta_pelatihan', function (Blueprint $table) {
            $table->primary(['peserta_id', 'plt_kode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_pelatihan', function (Blueprint $table) {
            $table->dropPrimary(['peserta_id', 'plt_kode']);
        });
    }
};
