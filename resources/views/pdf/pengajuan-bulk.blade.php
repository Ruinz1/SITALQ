<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Pengajuan Anggaran</title>
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
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daftar Pengajuan Anggaran</h2>
        <p>Total Data: {{ $pengajuans->count() }}</p>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Diajukan Oleh</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Nama Item</th>
                    <th>Harga Satuan</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $index => $pengajuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pengajuan->user->username }}</td>
                    <td>{{ $pengajuan->tanggal_pengajuan }}</td>
                    <td>{{ match($pengajuan->kategori) {
                        '1' => 'Pengadaan',
                        '2' => 'Pembelian',
                        '3' => 'Pengadaan dan Pembelian',
                        default => $pengajuan->kategori,
                    } }}</td>
                    <td>{{ $pengajuan->nama_item }}</td>
                    <td>Rp {{ number_format($pengajuan->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $pengajuan->satuan }}</td>
                    <td>{{ $pengajuan->jumlah }}</td>
                    <td>Rp {{ number_format($pengajuan->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($pengajuan->status) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" style="text-align: right;"><strong>Total Keseluruhan:</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($pengajuans->sum('total_harga'), 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 