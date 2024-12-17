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
        Schema::create('survei_pesertas', function (Blueprint $table) {
            $table->id();
            $table->text('larangan_menunggu');
            $table->text('larangan_perhiasan');
            $table->text('berpakaian_islami');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_pesertas');
    }
};
