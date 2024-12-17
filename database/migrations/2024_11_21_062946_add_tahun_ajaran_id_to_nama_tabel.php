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
    Schema::table('jadwals', function (Blueprint $table) {
        // Cek apakah kolom sudah ada sebelum menambahkannya
        if (!Schema::hasColumn('jadwals', 'tahun_ajaran_id')) {
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->foreign('tahun_ajaran_id')->references('id')->on('tahun_ajarans');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            //
            $table->dropForeign(['tahun_ajaran_id']);
        });
    }
};
