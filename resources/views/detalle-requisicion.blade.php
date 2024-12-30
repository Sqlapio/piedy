<x-guest-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden mt-14">
            <div class="flex justify-between items-center p-3">
                <div class="flex flex-col justify-center items-star">
                    <div class="flex flex-col justify-center items-start mb-5">
                        <img src="{{ asset('images/logo.png') }}" class="w-32 h-auto" alt="">
                    </div>
                    <div class="text-left">
                        <p class="text-md font-bold">Requisición</p>
                    </div>
                    <div class="text-left">
                        <p class="text-md">Fecha: {{ $detalle->fecha }}</p>
                    </div>
                    <div class="text-left">
                        <p class="text-md">Responsable: {{ $responsable }}</p>
                    </div>
                    <div class="text-left">
                        <p class="text-md">Sucursal: {{ $detalle->sucursal->nombre }}</p>
                    </div>
                </div>
            </div>
            <div class="p-3">
                @livewire('table-detalle-requisicion', [
                'codigo' => $codigo,
                'sucursal_id' => $sucursal_id
                ])
            </div>
        </div>
    </div>
</x-guest-layout>

