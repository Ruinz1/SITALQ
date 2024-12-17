<!DOCTYPE html>
<html>
<head>
    <title>Daftar Peserta Kelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">

        <h2>Daftar Absensi Kelas {{ $kelas }}</h2>
        <h2>Guru Wali Kelas : {{ $guru }}</h2>
        <h2>Tahun Ajaran : {{ $tahun_ajaran }}</h2>
       
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Kehadiran</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td style="width: 100px; height: 30px; border: 1px solid black;"></td>
                    <td style="width: 150px; height: 30px; border: 1px solid black;"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal }}</p>
    </div>
</body>
</html> 