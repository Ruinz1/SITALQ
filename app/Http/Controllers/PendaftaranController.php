<?php

namespace App\Http\Controllers;

use App\Models\KodePendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function validateKodePendaftaran($kode)
    {
        $kodePendaftaran = KodePendaftaran::where('kode', $kode)->first();

        if (!$kodePendaftaran) {
            return response()->json(['status' => 'error', 'message' => 'Kode pendaftaran tidak ditemukan.']);
        }

        // Periksa status pendaftaran
        if ($kodePendaftaran->pendaftaran->status != '1') { // Misalnya, 1 = aktif
            return response()->json(['status' => 'error', 'message' => 'Kode pendaftaran tidak valid karena status pendaftaran tidak aktif.']);
        }

        // Jika valid
        return response()->json(['status' => 'success', 'message' => 'Kode pendaftaran valid.']);
    }
} 