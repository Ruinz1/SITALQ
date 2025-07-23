<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FrontService;

class FrontController extends Controller
{
    //
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }

    public function index()
    {
        $pendaftaran = $this->frontService->getPendaftaran();
        return view('front.index', $pendaftaran);
    }

    public function sejarah()
    {
        return view('front.sejarah');
    }

    public function akademik()
    {
        $jadwal = $this->frontService->getJadwal();
        return view('front.akademik', $jadwal);
    }

    public function daftar()
    {
        $status = $this->frontService->getPendaftaran();
        
        if (!$status['isOpen']) {
            return redirect()->route('front.index')
                ->with('sweetalert', [
                    'icon' => 'error',
                    'title' => 'Pendaftaran Tutup',
                    'text' => 'Mohon maaf, pendaftaran sedang ditutup.'
                ]);
        }
        
        return view('front.daftar');
    }

    public function pembayaran(Request $request)
    {
        $kodeTransaksi = $request->query('kode');
        $transaksi = null;
        
        if ($kodeTransaksi) {
            $transaksi = \App\Models\Transaksi::where('kode_transaksi', $kodeTransaksi)
                ->with('peserta')
                ->first();
        }
        
        return view('front.pembayaran', compact('transaksi'));
    }
}
