<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Pelajaran</title>
    <style>
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
            background-color: #f2f2f2;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Jadwal Pelajaran</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Jadwal</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->guru->mapel->nama }}</td>
                <td>{{ $item->guru->nama }}</td>
                <td>{{ $item->hari }}, {{ $item->jam }}</td>
                <td>{{ $item->kelas->nama_kelas }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 