@php
use Filament\Facades\Filament;
@endphp

<x-filament-panels::page>

@pushonce('styles')
<style>
    .fi-modal-window {
        max-width: 60% !important;
        width: 60% !important;
    }

    .widget-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .widget-full {
        grid-column: 1 / -1;
    }

    .widget-graph {
        grid-column: 1 / span 3;
    }

</style>
@endpushonce
<div class="container grid md:grid-cols-3 lg:grid-cols-6  gap-3">
        <!-- Primer widget -->

        <div class="col-span-3">
            @livewire(\App\Filament\Widgets\StatsGeneral::class)
        </div>

        <!-- Segundo widget -->
        <div class="col-span-3 serv">
            @livewire(\App\Filament\Widgets\ServiciosDashChart::class)
        </div>

        <!-- Tercer widget -->
        <div class="col-span-3 prod">
            @livewire(\App\Filament\Widgets\ProductosDashChart::class)
        </div>

        <!-- Cuarto widget -->
        <div class="col-span-3 client">
            @livewire(\App\Filament\Widgets\ClientesDashChart::class)
        </div>

        <!-- Quinto widget -->
        {{-- <div class="col-span-2">
            @livewire(\App\Filament\Widgets\ProductosDashChart::class)
        </div> --}}

        <!-- Sexto widget -->
        {{-- <div class="col-span-1">
            @livewire(\App\Filament\Widgets\StatsGeneralProd::class)
        </div> --}}


</div>


</x-filament-panels::page>
