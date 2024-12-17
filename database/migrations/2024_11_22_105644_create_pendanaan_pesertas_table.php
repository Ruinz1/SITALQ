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
        Schema::create('pendanaan_pesertas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemasukan_perbulan_orang_tua');
            $table->text('keterangan_kenaikan_pendapatan');
            $table->text('keterangan_infaq');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendanaan_pesertas');
    }
};
