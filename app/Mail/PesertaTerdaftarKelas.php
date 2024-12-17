<?php

namespace App\Mail;

use App\Models\Peserta;
use App\Models\Kelas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PesertaTerdaftarKelas extends Mailable
{
    use Queueable, SerializesModels;

    public $peserta;
    public $kelas;

    public function __construct(Peserta $peserta, Kelas $kelas)
    {
        $this->peserta = $peserta;
        $this->kelas = $kelas;
    }

    public function build()
    {
        return $this->markdown('emails.peserta-terdaftar-kelas')
            ->subject('Pendaftaran Kelas Berhasil');
    }
}