<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Statistics Section -->
        <div class="flex flex-col md:flex-row gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 flex-1">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jadwal</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="total-jadwal">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 flex-1">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="jadwal-aktif">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 flex-1" onclick="showConflictModal()">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900/40 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Konflik Jadwal</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="konflik-jadwal">-</p>
                        <p class="text-xs text-red-600 dark:text-red-400">Klik untuk detail</p>
                    </div>
                </div>
            </div>
           
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select id="kelas-filter" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $kelasItem)
                            <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Jadwal Pelajaran</h3>
                <a href="{{ route('filament.admin.resources.jadwals.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                    Tambah Jadwal
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-24">
                                Jam
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Senin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Selasa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Rabu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kamis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jumat
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800" id="schedule-body">
                        <!-- Schedule content will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Conflict Modal -->
    <div id="conflict-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detail Konflik Jadwal</h3>
                    <button onclick="closeConflictModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="conflict-details" class="space-y-3">
                    <!-- Conflict details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        const CAN_MANAGE_JADWAL = @json(auth()->user()?->hasAnyRole(['Admin','Super_Admin']));
        const CSRF_TOKEN = @json(csrf_token());
        document.addEventListener('DOMContentLoaded', function() {
            loadSchedule();
            
            const kelasFilter = document.getElementById('kelas-filter');
            if (kelasFilter) {
                kelasFilter.addEventListener('change', function() {
                    loadSchedule();
                });
            }
        });

        function loadSchedule() {
            const kelasId = document.getElementById('kelas-filter').value;
            
            // Show loading
            document.getElementById('schedule-body').innerHTML = '<tr><td colspan="6" class="text-center py-4">Loading...</td></tr>';
            
            // Fetch schedule data (hanya berdasarkan kelas)
            fetch(`/api/jadwal/schedule?kelas_id=${kelasId}`)
                .then(response => response.json())
                .then(data => {
                    renderSchedule(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('schedule-body').innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Error loading schedule</td></tr>';
                });
        }

        function renderSchedule(scheduleData) {
            const tbody = document.getElementById('schedule-body');
            tbody.innerHTML = '';
            
            // Calculate statistics
            let totalJadwal = 0;
            let jadwalAktif = 0;
            let konflikJadwal = 0;
            let slotKosong = 0;
            
            // Generate time slots from 07:00 to 13:00
            const timeSlots = [];
            for (let hour = 7; hour <= 11; hour++) {
                timeSlots.push(`${hour.toString().padStart(2, '0')}:00`);
                if (hour < 11) {
                    timeSlots.push(`${hour.toString().padStart(2, '0')}:30`);
                }
            }
            
            timeSlots.forEach(time => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-800';
                
                // Time column
                const timeCell = document.createElement('td');
                timeCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r dark:border-gray-800';
                timeCell.textContent = time;
                row.appendChild(timeCell);
                
                // Day columns
                const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                days.forEach(day => {
                    const cell = document.createElement('td');
                    cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 border-r dark:border-gray-800';
                    
                    let schedule = scheduleData[day]?.[time];
                    if (!schedule && time === '11:00') {
                        schedule = { id: null, mapel: 'Pulang', guru: '', kelas: '', has_conflict: false };
                    }
                    if (schedule) {
                        const isPulang = schedule.mapel === 'Pulang' && !schedule.id;
                        const bgColor = isPulang ? 'bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700' : (schedule.has_conflict ? 'bg-red-100 dark:bg-red-900/30 border-red-300 dark:border-red-700' : 'bg-blue-100 dark:bg-blue-900/30 border-blue-300 dark:border-blue-700');
                        const textColor = isPulang ? 'text-gray-800 dark:text-gray-200' : (schedule.has_conflict ? 'text-red-800 dark:text-red-300' : 'text-blue-800 dark:text-blue-300');
                        const subTextColor = textColor.replace('-800', '-600');
                        const linkColor = schedule.has_conflict ? 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300' : 'text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300';
                        const conflictBadge = schedule.has_conflict ? '<span class="text-red-600 dark:text-red-400 text-xs font-bold mr-2">⚠️ Konflik</span>' : '';
                        
                        const actionsHtml = (CAN_MANAGE_JADWAL && !isPulang) ? `
                            <div class="ml-auto flex items-center gap-2">
                                <a href="/admin/jadwals/${schedule.id}/edit" class="${linkColor} text-xs">Edit</a>
                                <button onclick="deleteJadwal(${schedule.id})" class="text-red-600 hover:text-red-800 text-xs">Delete</button>
                            </div>
                        ` : '';

                        cell.innerHTML = `
                            <div class="${bgColor} border rounded-lg p-3 flex items-center gap-2">
                                ${conflictBadge}
                                <div class="font-semibold ${textColor}">${schedule.mapel}</div>
                                <span class="${subTextColor} text-xs">•</span>
                                <div class="${subTextColor} text-xs">${schedule.guru}</div>
                                <span class="${subTextColor} text-xs">•</span>
                                <div class="${subTextColor} text-xs">${schedule.kelas}</div>
                                ${actionsHtml}
                            </div>
                        `;
                        
                        totalJadwal++;
                        jadwalAktif++;
                        if (schedule.has_conflict) {
                            konflikJadwal++;
                        }
                    } else {
                        const tambahHtml = CAN_MANAGE_JADWAL ? `
                            <a href="{{ route('filament.admin.resources.jadwals.create') }}?hari=${day}&jam=${time}" class="text-indigo-600 hover:text-indigo-800 text-xs bg-indigo-50 px-2 py-1 rounded">
                                + Tambah
                            </a>
                        ` : '';
                        cell.innerHTML = `
                            <div class="text-center py-2">
                                <div class="text-gray-400 text-xs mb-2">Kosong</div>
                                ${tambahHtml}
                            </div>
                        `;
                        slotKosong++;
                    }
                    
                    row.appendChild(cell);
                });
                
                tbody.appendChild(row);
            });
            
            // Update statistics
            document.getElementById('total-jadwal').textContent = totalJadwal;
            document.getElementById('jadwal-aktif').textContent = jadwalAktif;
            document.getElementById('konflik-jadwal').textContent = konflikJadwal;
        }

        function showConflictModal() {
            const tahunEl = document.getElementById('tahun-ajaran-filter');
            const kelasEl = document.getElementById('kelas-filter');
            const tahunAjaranId = tahunEl ? tahunEl.value : '';
            const kelasId = kelasEl ? kelasEl.value : '';
            
            fetch(`/api/jadwal/conflicts?tahun_ajaran_id=${tahunAjaranId}&kelas_id=${kelasId}`)
                .then(response => response.json())
                .then(conflicts => {
                    const detailsContainer = document.getElementById('conflict-details');
                    detailsContainer.innerHTML = '';
                    
                    if (conflicts.length === 0) {
                        detailsContainer.innerHTML = '<p class="text-green-600">Tidak ada konflik jadwal.</p>';
                    } else {
                        conflicts.forEach(conflict => {
                            const conflictDiv = document.createElement('div');
                            conflictDiv.className = 'bg-red-50 border border-red-200 rounded-lg p-3';
                            conflictDiv.innerHTML = `
                                <div class="font-semibold text-red-800">${conflict.hari} - ${conflict.jam}</div>
                                <div class="text-red-600 text-sm mb-2">Kelas: ${conflict.kelas}</div>
                                <div class="space-y-1">
                                    ${conflict.jadwals.map(jadwal => `
                                        <div class="text-sm">
                                            <span class="font-medium">${jadwal.mapel}</span> - ${jadwal.guru}
                                            <a href="/admin/jadwals/${jadwal.id}/edit" class="text-blue-600 hover:text-blue-800 ml-2">Edit</a>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                            detailsContainer.appendChild(conflictDiv);
                        });
                    }
                    
                    document.getElementById('conflict-modal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function closeConflictModal() {
            document.getElementById('conflict-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('conflict-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConflictModal();
            }
        });

        function deleteJadwal(id) {
            if (!CAN_MANAGE_JADWAL) return;
            if (!confirm('Hapus jadwal ini? Tindakan tidak dapat dibatalkan.')) return;

            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', CSRF_TOKEN);

            fetch(`/jadwal/delete/${id}`, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(json => {
                if (json && json.success) {
                    loadSchedule();
                } else {
                    alert(json.message || 'Gagal menghapus jadwal');
                }
            })
            .catch(() => alert('Terjadi kesalahan saat menghapus jadwal'));
        }
    </script>
</x-filament-panels::page>
