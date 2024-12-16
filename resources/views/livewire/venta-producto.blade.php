<div>
    <div class="">
        @livewire('notifications')
        <div class="flex justify-between items-center gap-2 p-4">
            <div class="font-medium dark:text-white">
                <div class="text-lg text-black font-extrabold dark:text-gray-400 uppercase">Ventas de productos</div>
            </div>
            <div class="font-medium ">
                @if($hidden == '')
                <svg wire:click="updateProperty" class="w-[35px] h-[35px] text-[#16a34a] cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                </svg>
                @else
                <svg wire:click="updateProperty" class="w-[35px] h-[35px] text-[#dc2626] cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                  </svg>

                @endif

            </div>
        </div>

        <div class="grid grid-cols-1 gap-2 p-4 {{ $tableProductos }}">
            {{-- @livewire('table-producto') --}}
            <livewire:table-producto :add_servicio="$add_servicio" />
        </div>
        <div class="grid grid-cols-1 gap-2 mb-10 p-2">
            {{-- servicio asignado --}}
            <div class="w-full col-span-3">
                @livewire('table-pre-select-pro')
            </div>
        </div>

        <div class="border rounded-lg mb-5 mt-5 hidden">
            <p class="p-4 text-3xl font-bold text-[#bc9c95]">Venta de Productos</p>
            @livewire('VentaProducto.table-venta-producto')
        </div>
    </div>

    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    <x-menu_table/>

</div>
