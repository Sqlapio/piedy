<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use ReflectionClass;
use Filament\Widgets;
use Pages\DashboardNew;
use Filament\PanelProvider;
use Illuminate\Support\Str;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\View\TablesRenderHook;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\VentaServicioResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Filament\Tables\View\TablesRenderHook as ViewTablesRenderHook;

class AdminPanelProvider extends PanelProvider
{
    // protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $title = 'Dashboard Piedy';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function panel(Panel $panel): Panel
    {
        // $panelHooks = new ReflectionClass(PanelsRenderHook::class);
        // // Table Hooks
        // $tableHooks = new ReflectionClass(ViewTablesRenderHook::class);
        // // Widget Hooks
        // $widgetHooks = new ReflectionClass(Widgets\View\WidgetsRenderHook::class);

        // $panelHooks = $panelHooks->getConstants();
        // $tableHooks = $tableHooks->getConstants();
        // $widgetHooks = $widgetHooks->getConstants();

        // foreach ($panelHooks as $hook) {
        //     $panel->renderHook($hook, function () use ($hook) {
        //         return Blade::render('<div style="border: solid red 1px; padding: 2px;">{{ $name }}</div>', [
        //             'name' => Str::of($hook)->remove('tables::'),
        //         ]);
        //     });
        // }
        // foreach ($tableHooks as $hook) {
        //     $panel->renderHook($hook, function () use ($hook) {
        //         return Blade::render('<div style="border: solid red 1px; padding: 2px;">{{ $name }}</div>', [
        //             'name' => Str::of($hook)->remove('tables::'),
        //         ]);
        //     });
        // }
        // foreach ($widgetHooks as $hook) {
        //     $panel->renderHook($hook, function () use ($hook) {
        //         return Blade::render('<div style="border: solid red 1px; padding: 2px;">{{ $name }}</div>', [
        //             'name' => Str::of($hook)->remove('tables::'),
        //         ]);
        //     });
        // } 
        
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile()
            ->colors([
                'primary' => '#7797a4',
            ])
            ->favicon(asset('images/favicon.ico'))
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // 'namespace' => 'App\\Filament\\Pages',
                // 'path' => app_path('Filament/Pages'),
                // Pages\DashboardNew::class,
                // Pages\Dashboard::class
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
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Modulo Administrativo')
                    ->icon('heroicon-m-building-office-2'),
                NavigationGroup::make()
                    ->label('Modulo de Inventario')
                    ->icon('heroicon-m-building-library'),
                NavigationGroup::make()
                    ->label('Módulo Contable')
                    ->icon('heroicon-m-presentation-chart-bar'),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-s-square-3-stack-3d'),
                NavigationGroup::make()
                    ->label('Sistema')
                    ->icon('heroicon-s-user-group'),
                NavigationGroup::make()
                    ->label('Notificaciones')
                    ->icon('heroicon-m-tv'),
                NavigationGroup::make()
                    ->label('Metodos Prepagados')
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
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->databaseNotifications();
            // ->renderHook(PanelsRenderHook::TOPBAR_END, function () {
            //     return Blade::render('<div style="padding: 2px; font-size: 10px;">{{ $text }}</div>', [
            //             'text' => 'Versión: 2.0'
            //         ]);
            // });
            // ->renderHook(PanelsRenderHook::TOPBAR_END, function () {
            //     return Blade::render('<div style="padding: 2px;">{{ $text }}</div>', [
            //             'text' => Auth::user()->name,
            //         ]);
            // });
    }
}