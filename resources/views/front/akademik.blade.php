@extends('layouts.master')
@section('title', 'Akademik - TKIT Al Qolam')
@section('content')
</section>
<section class="container max-w-[1130px] mx-auto pt-[30px] px-6 sm:px-8 md:px-12 lg:px-0">
    <h2 class="font-extrabold text-xl sm:text-2xl mb-4">Jadwal Pelajaran</h2>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="kelas-filter" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @php($kelasList = ($kelas ?? collect()))
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 flex gap-3 sm:justify-end">
                <a id="print-btn" href="{{ route('jadwal.download') }}" target="_blank" class="inline-flex items-center bg-indigo-600 text-black px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Cetak / Unduh
                </a>
            </div>
        </div>
        <p id="choose-hint" class="text-sm text-gray-500">Silakan pilih kelas terlebih dahulu.</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Senin</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Selasa</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Rabu</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Kamis</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Jumat</th>
                    </tr>
                </thead>
                <tbody id="schedule-body">
                    <tr><td colspan="5" class="text-center py-6 text-black">SILAHKAN PILIH KELAS</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-center mt-6">
        <a id="print-btn-bottom" href="{{ route('jadwal.download') }}" target="_blank" class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            Cetak / Unduh
        </a>
    </div>
</section>
@endsection
@push('scripts')
    <script src="{{ asset('main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const kelasSelect = document.getElementById('kelas-filter');
            const tbody = document.getElementById('schedule-body');
            const chooseHint = document.getElementById('choose-hint');
            const printBtn = document.getElementById('print-btn');
            const printBtnBottom = document.getElementById('print-btn-bottom');

            const all = @json($jadwal ?? []);
            const data = Array.isArray(all) ? all : (all.jadwal ?? []);

            function renderTable(rows){
                tbody.innerHTML = '';
                const by = {Senin:[],Selasa:[],Rabu:[],Kamis:[],Jumat:[]};
                rows.forEach(r=>{
                    const t = (r.jam||'').substring(0,5);
                    if (!by[r.hari]) return;
                    const text = `${r.guru?.nama ?? ''}, ${r.guru?.mapel?.nama ?? ''}, : ${t}`;
                    by[r.hari].push({ time: t, text });
                });

                const sortByTime = list => list
                    .slice()
                    .sort((a,b) => (a.time||'').localeCompare(b.time||''))
                    .map(x => x.text);

                const senin  = sortByTime(by.Senin);
                const selasa = sortByTime(by.Selasa);
                const rabu   = sortByTime(by.Rabu);
                const kamis  = sortByTime(by.Kamis);
                const jumat  = sortByTime(by.Jumat);

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="border p-3 align-top">${senin.join('<br>')}</td>
                    <td class="border p-3 align-top">${selasa.join('<br>')}</td>
                    <td class="border p-3 align-top">${rabu.join('<br>')}</td>
                    <td class="border p-3 align-top">${kamis.join('<br>')}</td>
                    <td class="border p-3 align-top">${jumat.join('<br>')}</td>
                `;
                tbody.appendChild(tr);
            }

            function applyFilter(){
                const kelasId = kelasSelect.value;
                const filtered = kelasId ? data.filter(d => String(d.kelas_id) === String(kelasId)) : data;
                if (!kelasId) {
                    chooseHint.classList.remove('hidden');
                    printBtn.setAttribute('disabled','disabled');
                    printBtnBottom.setAttribute('disabled','disabled');
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-black">SILAHKAN PILIH KELAS</td></tr>';
                } else {
                    chooseHint.classList.add('hidden');
                    printBtn.removeAttribute('disabled');
                    printBtnBottom.removeAttribute('disabled');
                    const base = `{{ route('jadwal.download') }}`;
                    printBtn.href = `${base}?kelas_id=${kelasId}`;
                    printBtnBottom.href = `${base}?kelas_id=${kelasId}`;
                }
                renderTable(filtered);
            }

            kelasSelect.addEventListener('change', applyFilter);
            applyFilter();
        });
    </script>
@endpush