<?php

namespace App\Providers;

use Doctrine\DBAL\Schema\View;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;


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
        FilamentColor::register([
            'colorOne' => Color::hex('#7B95A6'),
            'colorTwo' => Color::hex('#7B9EA6'),
            'colorTree' => Color::hex('#BF9C99'),
            'colorFour' => Color::hex('#D9C3C1'),
            'colorFive' => Color::hex('#F2F2F2'),
            'colorDisabled' => Color::hex('#A9A9A9'),
        ]);

        FilamentAsset::register([
            Js::make('chart-js-plugins', Vite::asset('resources/js/filament-chart-js-plugins.js'))->module(),
        ]);

        //RenderHook
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            function () {
                return view('footer');
            }
        );

    }

    //Filament: Style customization using css hook classes not working?
}