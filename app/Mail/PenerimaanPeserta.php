<?php

namespace App\Mail;

use App\Models\Peserta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PenerimaanPeserta extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Peserta $peserta
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penerimaan Peserta TKIT AL-Qolam',
            from: config('mail.from.address')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penerimaan-peserta',
        );
    }
} 