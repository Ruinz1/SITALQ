<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
        }
        .info-siswa {
            margin-bottom: 20px;
        }
        .info-siswa table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-siswa td {
            padding: 5px;
        }
        .info-siswa td:first-child {
            width: 150px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
        }
        .semester {
            text-transform: capitalize;
            font-weight: bold;
        }
        .penilaian-tambahan {
            margin-top: 20px;
        }
        .penilaian-tambahan h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .penilaian-tambahan p {
            margin: 5px 0;
            padding: 5px;
            border: 1px solid #ddd;
            min-height: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN NILAI SISWA</h1>
        <p>Tahun Ajaran: {{ $peserta->kelas->tahunAjaran->nama }}</p>
        <p>Semester: <span class="semester">{{ $semester }}</span></p>
    </div>

    <div class="info-siswa">
        <table>
            <tr>
                <td>Nama Siswa</td>
                <td>: {{ $peserta->peserta->nama }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: {{ $peserta->kelas->nama_kelas }}</td>
            </tr>
            <tr>
                <td>Wali Kelas</td>
                <td>: {{ $peserta->kelas->guru->nama }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta->penilaian as $index => $penilaian)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $penilaian->mapel->nama }}</td>
                <td>{{ $penilaian->nilai }}</td>
                <td>
                    @if(is_array($penilaian->keterangan))
                        @foreach($penilaian->keterangan as $ket)
                            {{ $ket }}<br>
                        @endforeach
                    @else
                        {{ $penilaian->keterangan }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info-siswa">
        <table>
            <tr>
                <td>Total Nilai</td>
                <td>: {{ $totalNilai }}</td>
            </tr>
            <tr>
                <td>Jumlah Mata Pelajaran</td>
                <td>: {{ $jumlahMapel }}</td>
            </tr>
            <tr>
                <td>Rata-rata</td>
                <td>: {{ $rataRata }}</td>
            </tr>
        </table>
    </div>

    <div class="penilaian-tambahan">
        <h3>Penilaian Akhlak</h3>
        <p>{{ $akhlak ?: '-' }}</p>

        <h3>Penilaian Hafalan</h3>
        <p>{{ $hafalan ?: '-' }}</p>

        <h3>Catatan</h3>
        <p>{{ $catatan ?: '-' }}</p>
    </div>

    <div class="footer">
        <p>{{ $peserta->kelas->guru->nama }}</p>
        <p>Wali Kelas</p>
    </div>
</body>
</html> 