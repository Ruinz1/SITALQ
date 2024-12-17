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
        Schema::table('survei_pesertas', function (Blueprint $table) {
            //
            $table->string('menghadiri_pertemuan_wali');
            $table->string('kontrol_perkembangan');
            $table->string('larangan_merokok');
            $table->string('tidak_bekerjasama');
            $table->string('pendjadwalan');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei_pesertas', function (Blueprint $table) {
            //
        });
    }
};
