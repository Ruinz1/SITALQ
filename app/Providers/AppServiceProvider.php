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

        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->environment('production'));
    }
}
