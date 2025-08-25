<?php

require_once 'vendor/autoload.php';

use App\Models\Transaksi;
use App\Models\Kas;
use App\Models\Peserta;
use App\Models\TahunAjaran;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing TransaksiObserver...\n";

// Cek apakah ada transaksi
$transaksi = Transaksi::first();
if ($transaksi) {
    echo "Found transaksi ID: " . $transaksi->id . "\n";
    echo "Status pembayaran: " . $transaksi->status_pembayaran . "\n";
    
    // Cek apakah sudah ada record kas untuk transaksi ini
    $kas = Kas::where('transaksi_id', $transaksi->id)->first();
    if ($kas) {
        echo "Kas record found for transaksi: " . $kas->id . "\n";
    } else {
        echo "No kas record found for transaksi\n";
    }
    
    // Test update status pembayaran
    if ($transaksi->status_pembayaran != 1) {
        echo "Updating status pembayaran to 1...\n";
        $transaksi->status_pembayaran = 1;
        $transaksi->save();
        
        // Cek lagi apakah kas record dibuat
        $kas = Kas::where('transaksi_id', $transaksi->id)->first();
        if ($kas) {
            echo "Kas record created successfully: " . $kas->id . "\n";
        } else {
            echo "Kas record still not created\n";
        }
    } else {
        echo "Transaksi already has status pembayaran = 1\n";
    }
} else {
    echo "No transaksi found in database\n";
}

echo "Test completed.\n";
