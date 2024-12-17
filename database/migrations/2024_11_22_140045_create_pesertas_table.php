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
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kode_pendaftaran_id')->constrained('kode_pendaftarans')->onDelete('cascade');
            $table->foreignId('id_keluarga')->constrained('keluargas')->onDelete('cascade');
            $table->foreignId('id_informasi')->constrained('informasi_pesertas')->onDelete('cascade');
            $table->foreignId('id_keterangan')->constrained('keterangan_pesertas')->onDelete('cascade');
            $table->foreignId('id_pendanaan')->constrained('pendanaan_pesertas')->onDelete('cascade');
            $table->foreignId('id_survei')->constrained('survei_pesertas')->onDelete('cascade');
            $table->string('nama');
            $table->string('agama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('nama_panggilan');
            $table->unsignedBigInteger('berat_badan');
            $table->unsignedBigInteger('tinggi_badan');
            $table->unsignedBigInteger('jumlah_saudara_kandung');
            $table->unsignedBigInteger('jumlah_saudara_tiri')->nullable();
            $table->string('anak_ke');
            $table->string('mempunyai_alergi')->nullable();
            $table->string('pindahan_dari')->nullable();
            $table->date('tanggal_pindahan')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->string('status_peserta');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
