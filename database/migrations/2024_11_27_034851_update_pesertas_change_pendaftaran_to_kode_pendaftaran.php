<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign('pesertas_kode_pendaftaran_foreign');
            
            // Kemudian hapus index
            $table->dropIndex('pesertas_kode_pendaftaran_foreign');
        });

    // Tambah kolom baru
    Schema::table('pesertas', function (Blueprint $table) {
        $table->foreignId('kode_pendaftaran_id')
              ->after('id')
              ->constrained('kode_pendaftarans')
              ->cascadeOnDelete();
    });
    }

    public function down(): void
    {
        Schema::table('pesertas', function (Blueprint $table) {
            // Hapus foreign key baru
            $table->dropForeign(['kode_pendaftaran_id']);
            // Hapus kolom baru
            $table->dropColumn('kode_pendaftaran_id');
            
        });
    }
};