<?php

namespace App\Http\Controllers;

use App\Models\KelasHasPeserta;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadPesertaKelas($kelas_id)
    {
        $records = KelasHasPeserta::where('kelas_id', $kelas_id)
            ->with(['peserta.keluarga.ayah', 'peserta.kodePendaftaran.pendaftaran.tahunAjaran'])
            ->get();

        $data = [];
        foreach ($records as $record) {
            $data[] = [
                'nama' => $record->peserta->nama,
                'ayah' => $record->peserta->keluarga->ayah->nama ?? '-',
                
            ];
        }

        $pdf = PDF::loadView('exports.peserta-kelas', [
            'data' => $data,
            'guru' => $records->first()->kelas->guru->nama ?? 'Tidak Ada Guru',
            'kelas' => $records->first()->kelas->nama_kelas ?? 'Tidak Ada Kelas',
            'tahun_ajaran' => $records->first()->peserta->kodePendaftaran->pendaftaran->tahunAjaran->nama ?? 'Tidak Ada Tahun Ajaran',
            'tanggal' => now()->format('d F Y')
        ]);

        return $pdf->download('daftar-peserta-kelas.pdf');
    }
} 