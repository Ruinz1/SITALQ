<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pendaftaran;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('components.navbar', function ($view) {
            $pendaftaran = Pendaftaran::where('status', '1')->first();
            $view->with('pendaftaran', $pendaftaran);
        });
    }
} 