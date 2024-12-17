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
        Schema::table('keterangan_pesertas', function (Blueprint $table) {
            //
            $table->string('judulbuku_berlatihmembaca_latin')->nullable();
            $table->string('judulbuku_berlatihmembaca_hijaiyah')->nullable();
            $table->string('jilid_hijaiyah')->nullable();
            $table->string('keterangan_angka');
            $table->string('keterangan_hafal_surat')->nullable();
            $table->string('hobi');
            $table->string('keterangan_kisah_islami');
            $table->string('keterangan_majalah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keterangan_pesertas', function (Blueprint $table) {
            //
        });
    }
};
