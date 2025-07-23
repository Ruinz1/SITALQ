<?php

namespace App\Providers;

use App\Models\Kelas;
use App\Observers\KelasObserver;
use App\Models\TahunAjaran;
use App\Observers\TahunAjaranObserver;
use App\Models\Pendaftaran;
use App\Observers\PendaftaranObserver;
use App\Models\Peserta;
use App\Observers\PesertaObserver;
use App\Models\Transaksi;
use App\Observers\TransaksiObserver;
use App\Models\Pagu_anggaran;
use App\Observers\PaguAnggaranObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Kelas::observe(KelasObserver::class);
        TahunAjaran::observe(TahunAjaranObserver::class);
        Pendaftaran::observe(PendaftaranObserver::class);
        Peserta::observe(PesertaObserver::class);
        // Transaksi::observe(TransaksiObserver::class);
        // Pagu_anggaran::observe(PaguAnggaranObserver::class);

        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->environment('production'));
    }
}
