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
        Schema::table('pesertas', function (Blueprint $table) {
            //
            $table->string('latar_belakang');
            $table->string('harapan_keislaman');
            $table->string('harapan_keilmuan');
            $table->string('harapan_sosial');
            $table->string('berapa_lama_bersekolah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            //
        });
    }
};
