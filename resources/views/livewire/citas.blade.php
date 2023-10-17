<div>
    @livewire('notifications')
    <div class="p-5">
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Citas del dia</h1>
        <div class="flex justify-start mt-10 mb-4 {{ $botton_agendar_cita }}">
            <button type="submit" wire:click.prevent="mostrar()" class="justify-start rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Agendar cita</span>
            </button>
        </div>
        {{-- tabla y boton del formulario de clientes --}}
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-4 mb-4 mt-8 {{ $ocultar }}">
            {{-- Descripcion --}}
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Fecha de cita</label>
                <x-input type="date" wire:model.defer="fecha" id="focus" class="focus:ring-check-blue focus:border-check-blue" />
            </div>
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Hora de cita</label>
                <x-time-picker placeholder="hora" format="12" interval="30" wire:model.defer="hora" />
            </div>
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Cliente</label>
                <x-select wire:model.defer="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
            </div>
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Servicio</label>
                <x-select wire:model.defer="servicio_id" placeholder="Seleccion" :async-data="route('api.servicios')" option-label="descripcion" option-value="id" />
            </div>
        </div>

        <div class="flex justify-end p-2 mt-auto mb-4 {{ $ocultar }}">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Guardar</span>
            </button>
        </div>
        

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            @foreach ($data as $item)
            <div class="bg-white p-4 rounded-lg shadow" style="background-image: url('https://wallpaper-house.com/data/out/8/wallpaper2you_256843.jpg'); background-size: cover;">
                <div class="flex items-center justify-end mb-2">
                    <x-dropdown>
                        <x-dropdown.item label="Activar servicio" type="submit" wire:click="asignar_serv({{ $item->id }}, '2')" />
                            <x-dropdown.item label="Reagendar" type="submit" wire:click="reagendar_serv({{ $item->id }}, '2')" />
                    </x-dropdown>
                </div>
                <div class="text-right">
                    <img src="{{ asset('images/pngwing.com.png') }}" class="w-20" alt="">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        {{ $item->get_cliente->nombre }} {{ $item->get_cliente->apellido }}
                    </div>
                        <div class="mt-3 text-right text-md font-semibold text-black">
                            {{-- <div class="text-sm font-medium text-black">Empleado: {{ $item->get_empleado->nombre }} {{ $item->get_empleado->apellido }}</div> --}}
                            <div class="text-sm font-medium text-black">Servicio: {{ $item->get_servicio->descripcion }}</div>
                            <div class="text-sm font-medium text-black">Hora: {{ $item->hora }}</div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

