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
        Schema::create('keterangan_pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan_membaca');
            $table->string('keterangan_membaca_hijaiyah');
            $table->string('keterangan_menulis');
            $table->string('keterangan_menghitung');
            $table->string('keterangan_menggambar');
            $table->string('keterangan_berwudhu');
            $table->string('keterangan_tata_cara_shalat');
            $table->string('keterangan_hafalan_juz_ama');
            $table->string('keterangan_hafalan_murottal');
            $table->string('keterangan_hafalan_doa');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterangan_pesertas');
    }
};
