<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function exportKelasPeserta(Kelas $kelas)
    {
        // Buat file Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Peserta');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Tahun Ajaran');

        // Data
        $row = 2;
        foreach ($kelas->kelasHasPeserta as $index => $peserta) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $peserta->peserta->nama);
            $sheet->setCellValue('C' . $row, $kelas->nama_kelas);
            $sheet->setCellValue('D' . $row, $kelas->tahunAjaran->nama);
            $row++;
        }

        // Simpan file
        $fileName = 'peserta-kelas-' . $kelas->id . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/public/exports/' . $fileName);
        $writer->save($path);

        return response()->json([
            'status' => 'completed',
            'download_url' => url('storage/exports/' . $fileName)
        ]);
    }

    public function checkStatus($exportId)
    {
        // Implementasi pengecekan status
        return response()->json([
            'status' => 'completed',
            'download_url' => url('storage/exports/file.xlsx')
        ]);
    }
} 