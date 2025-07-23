<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengajuan Anggaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rincian Pengajuan</h2>
        <p>Diajukan oleh: {{ $pengajuan->user->username }}</p>
        <p>Tanggal Pengajuan: {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->isoFormat('D MMMM Y') }}</p>
    </div>

    <div class="content">
        <table>
            <tr>
                <th>Kategori</th>
                <td>{{ match($pengajuan->kategori) {
                    '1' => 'Pengadaan',
                    '2' => 'Pembelian',
                    '3' => 'Pengadaan dan Pembelian',
                    default => $pengajuan->kategori,
                } }}</td>
            </tr>
            <tr>
                <th>Nama Item</th>
                <td>{{ $pengajuan->nama_item }}</td>
            </tr>
            <tr>
                <th>Harga Satuan</th>
                <td>Rp {{ number_format($pengajuan->harga_satuan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Satuan</th>
                <td>{{ $pengajuan->satuan }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $pengajuan->jumlah }}</td>
            </tr>
            <tr>
                <th>Total Harga</th>
                <td>Rp {{ number_format($pengajuan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($pengajuan->status) }}</td>
            </tr>
        </table>

        @if($pengajuan->status === 'approved')
        <div class="approval-info">
            <p>Disetujui oleh: {{ $pengajuan->disetujui_oleh }}</p>
            @if($pengajuan->keterangan)
            <p>Keterangan: {{ $pengajuan->keterangan }}</p>
            @endif
            <div style="text-align: center; margin-top: 30px;">
                <p>Tanggal Disetujui: {{ \Carbon\Carbon::parse($pengajuan->tanggal_disetujui)->isoFormat('D MMMM Y') }}</p>
                <p style="margin-bottom: 10px;">MENGETAHUI KEPALA TKIT AL QOLAM</p>
                <div style="border: 1px solid #000; display: inline-block; padding: 10px; margin-bottom: 10px;">
                   
                    <p style="margin: 5px 0 0 0;">Ditandatangani secara elektronik oleh</p>
                    <p style="margin: 5px 0;">Mariani Abutata, S.Pd., M.Pd</p>
                    
                </div>
                <p style="margin: 0;"><strong>Mariani Abutata, S.Pd., M.Pd</strong></p>
                <p style="margin: 5px 0;">KEPALA TKIT AL QOLAM</p>
               
            </div>
        </div>
        @elseif($pengajuan->status === 'rejected')
        <div class="rejection-info">
            <p>Ditolak oleh: {{ $pengajuan->disetujui_oleh }}</p>
            <p>Tanggal ditolak: {{ \Carbon\Carbon::parse($pengajuan->tanggal_disetujui)->isoFormat('D MMMM Y') }}</p>
            <p>Alasan penolakan: {{ $pengajuan->alasan_penolakan }}</p>
            @if($pengajuan->keterangan)
            <p>Keterangan: {{ $pengajuan->keterangan }}</p>
            @endif
        </div>
        @endif
    </div>
   

    

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 