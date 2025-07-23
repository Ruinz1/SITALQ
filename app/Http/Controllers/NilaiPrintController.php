<?php

namespace App\Http\Controllers;

use App\Models\KelasHasPeserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class NilaiPrintController extends Controller
{
    public function print(Request $request, KelasHasPeserta $peserta)
    {
        $peserta->load([
            'peserta',
            'kelas.guru',
            'kelas.tahunAjaran',
            'penilaian' => function ($query) use ($request) {
                $query->where('semester', $request->semester)
                    ->with('mapel');
            }
        ]);

        // Hitung total dan rata-rata nilai
        $totalNilai = $peserta->penilaian->sum('nilai');
        $jumlahMapel = $peserta->penilaian->count();
        $rataRata = $jumlahMapel > 0 ? round($totalNilai / $jumlahMapel, 2) : 0;

        // Konfigurasi DomPDF
        $options = new \Dompdf\Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsPhpEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->set('defaultFont', 'serif');
        
        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('pdf.nilai-siswa', [
            'peserta' => $peserta,
            'totalNilai' => $totalNilai,
            'rataRata' => $rataRata,
            'jumlahMapel' => $jumlahMapel,
            'semester' => $request->semester,
            'akhlak' => $request->akhlak,
            'hafalan' => $request->hafalan,
            'catatan' => $request->catatan
        ])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("nilai-{$peserta->peserta->nama}-{$request->semester}.pdf");
    }
} 