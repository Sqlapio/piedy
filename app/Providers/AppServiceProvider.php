<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\Vite;


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

    }

    //Filament: Style customization using css hook classes not working?
}
