<?php

namespace App\Http\Controllers;

use App\Models\Pagu_anggaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class PaguPrintController extends Controller
{
    public function print(Pagu_anggaran $pengajuan)
    {
        $pengajuan->load([
        'user',
        'tahunAjaran',
        ]);

        // Konfigurasi DomPDF
        $options = new \Dompdf\Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsPhpEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->set('defaultFont', 'serif');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('pdf.pengajuan-single', ['pengajuan' => $pengajuan])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("formulir-{$pengajuan->nama_item}.pdf");
    }
}