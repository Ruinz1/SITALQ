<?php

namespace App\Providers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\jadwal;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use App\Policies\GuruPolicy;
use App\Policies\UserPolicy;
use App\Policies\KelasPolicy;
use App\Policies\MapelPolicy;
use App\Policies\RolesPolicy;
use App\Policies\JadwalPolicy;
use App\Policies\MapelsPolicy;
use App\Models\KelasHasPeserta;
use App\Policies\PesertaPolicy;
use App\Policies\PermisionPolicy;
use Spatie\Permission\Models\Role;
use App\Policies\PendaftaranPolicy;
use App\Policies\TahunAjaranPolicy;
use App\Policies\KelasHasPesertaPolicy;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolesPolicy::class,
        Permission::class => PermisionPolicy::class,
        Guru::class => GuruPolicy::class,    
        TahunAjaran::class => TahunAjaranPolicy::class,
        Pendaftaran::class => PendaftaranPolicy::class,
        Mapel::class => MapelsPolicy::class,
        Kelas::class => KelasPolicy::class,
        Peserta::class => PesertaPolicy::class,
        jadwal::class => JadwalPolicy::class,
        KelasHasPeserta::class => KelasHasPesertaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
} 