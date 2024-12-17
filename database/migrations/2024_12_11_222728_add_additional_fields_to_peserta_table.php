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
            $table->string('bahasa_sehari');
            $table->text('alamat');
            $table->string('asal_tk')->nullable();
            $table->string('tanggal_pindah')->nullable();
            $table->string('kelompok')->nullable();
            $table->string('penyakit_berapalama')->nullable();
            $table->string('penyakit_kapan')->nullable();
            $table->string('penyakit_pantangan')->nullable();
            $table->string('toilet_traning')->nullable();
            $table->text('lainnya')->nullable();
            $table->string('ttd_ortu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            $table->dropColumn([
                'bahasa_sehari',
                'alamat',
                'penyakit_berapalama',
                'penyakit_kapan',
                'penyakit_pantangan',
                'toilet_traning',
                'lainnya',
                'ttd_ortu'
            ]);
        });
    }
};
