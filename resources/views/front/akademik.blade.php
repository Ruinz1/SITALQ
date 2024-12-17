@extends('layouts.master')
@section('title', 'Akademik - TKIT Al Qolam')
@section('content')
</section>
<section id="Details" class="container max-w-[1130px] mx-auto pt-[50px] px-6 sm:px-8 md:px-12 lg:px-0">
    <h2 class="font-extrabold text-xl sm:text-2xl">Akademik : tahun ajaran 2024/2025</h2>
    <div class="flex flex-col sm:flex-row gap-[30px] sm:gap-[50px] justify-center">
        <div class="flex flex-col gap-5 max-w-full sm:max-w-[850px] text-center mb-32">
            <h2 class="font-extrabold text-xl sm:text-2xl">Jadwal</h2>
            
            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-4">No</th>
                            <th class="border p-4">Mata Pelajaran</th>
                            <th class="border p-4">Guru</th>
                            <th class="border p-4">Jadwal</th>
                            <th class="border p-4">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $item)
                        <tr>
                            <td class="border p-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border p-3">{{ $item->guru->mapel->nama }}</td>
                            <td class="border p-3">{{ $item->guru->nama }}</td>
                            <td class="border p-3">{{ $item->hari }}, {{ $item->jam }}</td>
                            <td class="border p-3">{{ $item->kelas->nama_kelas }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 4rem;" class="flex justify-center mb-32">
        <a href="{{ route('jadwal.download') }}" style="display: inline-flex; background: blue; color: white; padding: 10px 20px; border-radius: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; margin-right: 8px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Unduh Jadwal
        </a>
    </div>
</section>
@endsection
@push('scripts')
    <script src="{{ asset('main.js') }}"></script>
    @endpush