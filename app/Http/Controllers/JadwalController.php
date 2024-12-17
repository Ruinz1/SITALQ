<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    public function download()
    {
        $jadwal = Jadwal::with(['guru.mapel', 'kelas'])->get();
        
        $pdf = Pdf::loadView('exports.jadwal', compact('jadwal'));
        return $pdf->download('jadwal-pelajaran.pdf');
    }
} 