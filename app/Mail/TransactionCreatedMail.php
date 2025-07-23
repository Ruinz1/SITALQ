<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peserta;
    public $kodeTransaksi;
    public $totalBayar;

    public function __construct($peserta, $kodeTransaksi, $totalBayar)
    {
        $this->peserta = $peserta;
        $this->kodeTransaksi = $kodeTransaksi;
        $this->totalBayar = $totalBayar;
    }

    public function build()
    {
        return $this->subject('Kode Transaksi Pendaftaran')
                    ->view('emails.transaction-created');
    }
} 