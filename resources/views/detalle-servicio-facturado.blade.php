<x-guest-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden mt-14">
            <div class="flex justify-between items-center p-3">
                <div class="flex flex-col justify-center items-star">
                    <div class="flex flex-col justify-center items-start mb-5">
                        <img src="{{ asset('images/logo.png') }}" class="w-32 h-auto" alt="">
                    </div>
                    <div class="text-left">
                        <p class="text-md font-bold">Detalle de Servicio</p>
                    </div>
                    <div class="text-left">
                        <p class="text-sm">Fecha: {{ $detalle->created_at }}</p>
                    </div>
                    <div class="text-left">
                        <p class="text-sm">Cliente: {{ $detalle->cliente->nombre }}</p>
                    </div>
                    <div class="text-left">
                        <p class="text-sm">Técnico: {{ $detalle->empleado->name }}</p>
                    </div>

                </div>
            </div>
            {{ $detalle->cod_asigancion }}

            <div class="p-3">
                @livewire('table-externa-detalle-asignacion', [
                    'cod_asignacion' => $detalle->cod_asignacion,
                    'cliente_id' => $detalle->cliente_id

                ])
            </div>
            <div class="flex justify-center items-center p-4">
                <p class="text-xs text-center font-semibold uppercase">Si este servicio no pertecene a usted por favor reportarlo con el gerente de la tienda. Gracias</p>
            </div>
        </div>
    </div>
</x-guest-layout>
