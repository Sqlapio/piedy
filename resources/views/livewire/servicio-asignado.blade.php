<div>
    @if ($data_user == null)
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">No posee servicio asignado</h1>
    @endif
    @if ($data_user != null)
    @livewire('notifications')
    <a href="#" class="block max-w-sm p-6 mb-5 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <h1 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">Cliente: {{ $data_user->cliente }}</h1>
        <p class="font-normal text-gray-700 dark:text-gray-400">Servicio asignado: {{ $data_user->servicio }}</p>
        <p class="font-normal text-gray-700 dark:text-gray-400">Costo: ${{ $data_user->costo }}</p>
    </a>
    <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Servicios adicionales</h1>
    <div class="flex justify-start mb-4">
        <input wire:model.live="buscar" type="search" id="search" name="buscar" class="border-b border-gray-200 py-2 text-sm rounded-full sm:w-1/3 md:w-1/4 shadow-lg focus:ring-check-blue focus:border-check-blue" placeholder="Buscar servicio adicional" autocomplete="off">
        <svg wire:click="reset_filtros" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 font-bold text-gray-500 my-auto ml-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 ">
        @foreach($data as $item)
        <!-- card group 1 -->
        <div class="p-2 max-w-sm ">
            <input type="checkbox" id="{{ $item->id }}" wire:model.live="extra" wire:click="total()" value="{{ $item->id }}" class="hidden peer">
            <label for="{{ $item->id }}" class="inline-flex items-center justify-between w-full p-5 text-black bg-gray-200 border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-green-600 peer-checked:bg-green-600 peer-checked:text-white shadow-[rgba(50,50,93,0.25)_0px_6px_12px_-2px,_rgba(0,0,0,0.3)_0px_3px_7px_-3px]">
                <div class="block ">
                    <img src="{{ asset('images/dolares.png') }}" class="w-12" alt="">
                    <div class="w-full text-lg font-semibold">{{ $item->descripcion }}</div>
                    <div class="w-full text-sm">${{ $item->costo }}</div>
                </div>
            </label>
        </div>
        @endforeach
    </div>
    <div class="bg-white px-4 py-3 items-center justify-between border-t border-gray-200 sm:px-6">
        {{-- Paginacion --}}
        {{ $data->links() }}
    </div>
    <div class="flex justify-end mt-10 mb-4 p-2">
        <div class="flex justify-end rounded-md mr-5 border border-transparent bg-[#bd9c95] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            @if($total_vista > 0)
                <span>TOTAL A PAGAR: ${{ number_format($total_vista,2) }}</span>
            @else
                <span>TOTAL A PAGAR: ${{ $data_user->costo }}</span>
            @endif
            
        </div>
        <button type="submit" wire:click.prevent="store({{ $data_user->id }})" class="flex justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span>Guardar y cerrar servicio</span>
        </button>
    </div>
    @endif
    <x-menu_empleado_table></x-menu_empleado_table>
</div>


