<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class PesertaPrintController extends Controller
{
    public function print(Peserta $peserta)
    {
        $peserta->load([
            'kodePendaftaran.pendaftaran.tahunAjaran',
            'keluarga.ayah',
            'keluarga.ibu',
            'keluarga.wali',
            'informasi',
            'keterangan',
            'pendanaan',
            'survei',
            'saudara'
        ]);

        // Konfigurasi DomPDF
        $options = new \Dompdf\Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsPhpEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->set('defaultFont', 'serif');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('pdf.peserta', ['peserta' => $peserta])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("formulir-{$peserta->nama}.pdf");
    }
}