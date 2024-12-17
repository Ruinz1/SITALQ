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
        Schema::table('informasi_pesertas', function (Blueprint $table) {
            //
            $table->string('kebiasan_bangun_siang')->nullable();
            $table->string('hal_mengenai_tingkah_anak')->nullable();
            $table->string('mudah_bergaul')->nullable();
            $table->string('sifat_baik')->nullable();
            $table->string('sifat_buruk')->nullable();
            $table->string('pembantu_rumah_tangga')->nullable();
            $table->string('peralatan_elektronik')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasi_pesertas', function (Blueprint $table) {
            //
        });
    }
};
