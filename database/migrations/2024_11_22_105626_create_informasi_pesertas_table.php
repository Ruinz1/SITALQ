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
        Schema::create('informasi_pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('tinggal_bersama');
            $table->unsignedBigInteger('jumlah_penghuni_dewasa');
            $table->unsignedBigInteger('jumlah_penghuni_anak');
            $table->string('halaman_bermain_dirumah');
            $table->string('pergaulan_dengan_sebaya');
            $table->string('selera_makan');
            $table->string('hubungan_dengan_ayah');
            $table->string('hubungan_dengan_ibu');
            $table->string('kemampuan_buang_air');
            $table->string('kebiasan_tidur_malam');
            $table->string('kebiasan_tidur_siang');
            $table->string('kebiasan_bangun_pagi');
            $table->string('kebiasaan_ngompol');
            $table->string('hal_penting_waktu_tidur');
            $table->string('kepatuhan_anak');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_pesertas');
    }
};
