<div>
    @livewire('notifications')
    <div class="grid grid-cols-2 gap-2">
        <div class="w-full max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-start mb-4">
                <input wire:model.live="buscar" type="search" id="search" name="buscar" class="border-b border-gray-200 py-2 text-sm rounded-full shadow-lg focus:ring-check-blue focus:border-check-blue" placeholder="Buscar cliente" autocomplete="off">
            </div>
            <p class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-1 p-1">Selección de servícios</p>
            <ul class="space-y-3">
                @foreach ($data as $item)
                <div class="">
                    <input type="checkbox" id="{{ $item->id }}" wire:model.live="servicios" wire:click="total()" value="{{ $item->id }}" class="hidden peer">
                    <label for="{{ $item->id }}" class="inline-flex items-center justify-between w-full p-3 text-black bg-gray-400 border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-green-600 peer-checked:bg-green-600 peer-checked:text-white shadow-[rgba(50,50,93,0.25)_0px_6px_12px_-2px,_rgba(0,0,0,0.3)_0px_3px_7px_-3px]">

                            <div class="flex items-center space-x-4 w-full">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-extrabold text-white truncate dark:text-white">
                                        Cliente: {{ $item->cliente }}
                                    </p>
                                    <p class="text-xs text-white truncate dark:text-gray-400">
                                        Codigo: {{ $item->cod_asignacion }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    <p class="text-lg text-white truncate dark:text-gray-400">
                                        ${{ $item->costo }}
                                    </p>
                                </div>
                            </div>

                    </label>
                </div>
                @endforeach
                <div class="bg-white px-4 py-3 items-center justify-between border-t border-gray-200 sm:px-6">
                    {{-- Paginacion --}}
                    {{ $data->links() }}
                </div>
                <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-600 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                    </svg>
                    <span class="flex-1 ml-3 text-lg whitespace-nowrap">Total:</span>
                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 mr-5 text-lg font-extrabold text-white ">${{ $total_vista }}</span>
                </a>
                <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-600 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                    </svg>
                    <span class="flex-1 ml-3 text-lg whitespace-nowrap">Total en bolivares:</span>
                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 mr-5 text-lg font-extrabold text-white ">Bsd. {{ number_format($total_vista_bsd, 2, ",", ".") }}</span>
                </a>
                </li>
            </ul>
        </div>

        {{-- caja --}}
        <div class="w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Método de pago
            </h2>
            <div class="mt-8 space-y-6">
                <div class="px-2">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Forma de pago:</p>
                    <x-select wire:blur="$dispacht('metodo_principal', value: $event.target.value)" wire:model.live="descripcion" placeholder="Método de pago" :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
                </div>
                <div class="px-2 {{ $ref_hidden }}">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Nro. referencia:</p>
                    <x-input wire:model.defer="referencia" placeholder="12363456" />
                </div>
                <div class="grid grid-cols-2 gap-2 {{ $op1_hidden }}">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago($)</p>
                        <x-select wire:change="$dispatch('metodo1', $event.target.value)" wire:model.live="op1" placeholder="Seleccione..." :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago(Bs)</p>
                        <x-select wire:change="$dispatch('metodo2', $event.target.value)" wire:model.live="op2" placeholder="Seleccione..." :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 {{ $op1_hidden }}">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto en Dolares($)</p>
                        <x-input wire:keydown.enter="calculo($event.target.value)" wire:model.live="valor_uno" value="{{ $valor_uno }}" placeholder="0.00"/>
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto en Bolivares(Bs)</p>
                        <x-input wire:model.live="valor_dos" value="{{ $valor_dos }}" placeholder="0.00"/>
                    </div>
                </div>
                <div class="sm:mt-2">
                    <button type="button" wire:click="facturar_servicio()" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="facturar_servicio" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span>Facturar servicio</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



