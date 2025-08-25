<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\UserMenuItem;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\AdminResource\Widgets\StatsAdminOverview;
use App\Filament\Resources\AdminResource\Widgets\PendaftaranPerTahunChart;
use App\Filament\Resources\AdminResource\Widgets\JenisKelaminChart;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissions;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                UserMenuItem::make()
                    ->label('Setting')
                    ->visible(function () {
                        $user = auth()->user();
                        if (!$user) return false;
                        return method_exists($user, 'hasRole') ? !$user->hasRole('Guru') : true;
                    })
                    ->url(function () {
                        $user = auth()->user();
                        return $user ? url('admin/users/' . $user->id . '/edit') : '#';
                    })
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsAdminOverview::class,
                JenisKelaminChart::class,
                PendaftaranPerTahunChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            
            
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
                
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName('TKIT ALQOLAM')
            ->brandLogo(fn () => view('components.application-logo'))
            ->favicon(asset('assets/images/logos/logo-tk-circle.png'));
    }
}
