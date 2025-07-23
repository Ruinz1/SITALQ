@extends('filament-panels::page')
@section('content')
<div class="overflow-x-auto">
    <table class="table-auto w-full border border-gray-300">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Nama Siswa</th>
                @foreach($mapels as $mapel)
                    <th class="px-4 py-2 border">{{ $mapel->nama }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rekapNilaiData as $row)
                <tr>
                    <td class="px-4 py-2 border font-semibold">{{ $row['nama'] }}</td>
                    @foreach($mapels as $mapel)
                        <td class="px-4 py-2 border text-center">
                            {{ $row['penilaian'][$mapel->id] !== null ? $row['penilaian'][$mapel->id] : '-' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 