
<div>
    @livewire('notifications')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 mb-4 mt-4 gap-4">
        @foreach ($data as $item)
            <div id="accordion-collapse" data-accordion="collapse" class="w-full" data-active-classes="bg-gray-4 00">
                <h2 id="accordion-collapse-heading-{{ $item->id }}">
                    <button type="button" class="flex justify-start items-center w-full p-5 {{ $item->status == 'activo' ? 'text-white' : 'text-black' }} font-extrabold rounded-xl {{ $item->status == 'activo' ? 'bg-[#BF9C8F]' : 'bg-[#7B9AA6]' }} gap-3" data-accordion-target="#accordion-collapse-body-{{ $item->id }}" aria-expanded="false" aria-controls="accordion-collapse-body-{{ $item->id }}">
                        <img class="w-20 h-auto rounded-full ml-4" src="{{ $item->status == 'activo' ? asset('images/abierto.png') : asset('images/cerrado.png') }}" alt="">
                        <div class="flex flex-col items-start">
                            <span class="text-sm">Cliente: {{ $item->cliente->nombre }}</span>
                            <span class="text-sm">Técnico: {{ $item->empleado->name }}</span>
                            <span class="text-sm">Codigo: {{ $item->cod_asignacion }}</span>
                            <span class="text-sm">Fecha: {{ $item->created_at }}</span>

                        </div>
                    </button>
                </h2>
                <div id="accordion-collapse-body-{{ $item->id }}" class="hidden" aria-labelledby="accordion-collapse-heading-1">
                    <div class="border rounded-lg mb-5 mt-5 ">
                        {{-- <p class="p-4 text-3xl font-bold text-[#bc9c95]">Venta de Productos</p> --}}
                        @livewire('table-detalle-asignacion',
                        [
                            'cod_asignacion' => $item->cod_asignacion,
                            'cliente_id'     => $item->cliente_id
                        ])
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
        <x-menu-tecnico/>
    @else
        <x-menu_table/>
    @endif
</div>


