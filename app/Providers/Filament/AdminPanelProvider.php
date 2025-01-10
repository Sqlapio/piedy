<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Navigation\NavigationGroup;
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

class AdminPanelProvider extends PanelProvider
{
    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $title = 'Dashboard Piedy';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#7797a4',
            ])
            ->favicon(asset('images/favicon2.PNG'))
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,

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
            // ->navigationGroups([
            //     'Administración',
            //     'Movimientos de inventario',
            //     'Tienda Sambil',
            //     'Facturación',
            //     'Contabilidad',
            //     'Ventas',
            //     'Sistema'
            // ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Administración')
                    ->icon('heroicon-m-building-office-2'),
                NavigationGroup::make()
                    ->label('Contabilidad')
                    ->icon('heroicon-m-building-library'),
                NavigationGroup::make()
                    ->label('Ventas')
                    ->icon('heroicon-m-presentation-chart-bar'),
                NavigationGroup::make()
                    ->label('Manejo de Inventario')
                    ->icon('heroicon-s-square-3-stack-3d'),
                NavigationGroup::make()
                    ->label('Clientes')
                    ->icon('heroicon-s-user-group'),
                NavigationGroup::make()
                    ->label('Sistema')
                    ->icon('heroicon-m-tv'),
                NavigationGroup::make()
                    ->label('GiftCard')
                    ->icon('heroicon-m-gift'),
                NavigationGroup::make()
                    ->label('Membresias')
                    ->icon('heroicon-s-star'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa()
            ->maxContentWidth(MaxWidth::Full)
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
