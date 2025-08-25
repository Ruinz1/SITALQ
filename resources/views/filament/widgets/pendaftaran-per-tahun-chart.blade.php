@php
    $widget = app(\App\Filament\Resources\AdminResource\Widgets\PendaftaranPerTahunChart::class);
    $data = $widget->getData();
    $type = $widget->getType();
@endphp

<div class="mt-6">
    <div class="text-lg font-medium mb-2">Grafik Total Pendaftaran per Tahun Ajaran</div>
    <canvas id="pendaftaran-per-tahun-chart"></canvas>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        const ctx = document.getElementById('pendaftaran-per-tahun-chart');
        if (!ctx) return;
        // Pastikan Chart global tersedia dari Filament
        const config = {
            type: @json($type),
            data: @json($data),
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        };
        new window.Chart(ctx, config);
    });
</script>


