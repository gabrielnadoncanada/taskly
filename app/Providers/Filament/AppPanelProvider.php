<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\EventCalendarWidget;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Models\Organization;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('/')
            ->login(Login::class)
            ->profile()
//            ->brandLogo(asset('/img/logo-light.svg'))
//            ->darkModeBrandLogo(asset('/img/logo-dark.svg'))
            ->brandLogoHeight('20px')
            ->colors([
                'primary' => Color::rgb('rgb(255,153,0)'),
            ])
//            ->renderHook(PanelsRenderHook::BODY_START, fn (): View => view('filament.staging-banner'))
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): View => view('components.layouts.footer'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                //                Dashboard::class,
            ])
            ->passwordReset()
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
                    ->plugins(['dayGrid', 'timeGrid', 'list', 'interaction']),

            ])
            ->widgets([
                EventCalendarWidget::class,

                //                StatsOverviewWidget::class,
                //                CustomersChart::class,
                //                OrdersChart::class
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
            ->tenantMenu(fn () => auth()->user()->hasRole('Super Administrateur'))
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                'Opération',
                'Logistique',
                'Administration',
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->tenant(Organization::class)
            ->tenantRoutePrefix('organization');
    }
}
