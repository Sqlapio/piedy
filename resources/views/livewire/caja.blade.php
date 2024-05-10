@php
use App\Models\TasaBcv as ModelsTasaBcv;
    $tasa = ModelsTasaBcv::first()->tasa;
@endphp
<div>
     {{-- <div class="py-4">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" wire:click="cliente_especial" wire:model="option" class="sr-only peer">
            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">CLIENTE ESPECIAL</span>
        </label>
    </div> --}}
    <div class="grid grid-cols-3 gap-2">

        {{-- LISTA DE SERVICIOS --}}
        <div class="col-span-2 w-full max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            @livewire('notifications')
            <h5 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">
                Cliente: {{ $data->cliente }}
            </h5>
            <div class="flex items-center gap-2">
                <div class="relative w-12 h-12 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                    <svg class="absolute w-14 h-14 text-gray-700 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                {{-- <img class="w-10 h-10 rounded-full" src="https://banner2.cleanpng.com/20180330/dde/kisspng-user-computer-icons-blue-clip-art-avatar-5abe81d5eb94e7.566134231522434517965.jpg" alt=""> --}}
                <div class="font-medium dark:text-white">
                    <div class="text-md text-green-500 font-extrabold dark:text-gray-400">Técnico: {{ $data->empleado }}</div>
                    <div class="text-md text-green-500 font-extrabold dark:text-gray-400">Código: {{ $data->cod_asignacion }}</div>
                </div>
            </div>
            {{-- <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Técnico: {{ $data->empleado }}</p>
            <p class="text-xs font-normal text-gray-500 dark:text-gray-400">Código: {{ $data->cod_asignacion }}</p> --}}
            <ul class="my-4 space-y-3 mt-8">
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Servicio cargados:</p>
                @if(count($detalle) > 0)
                @foreach ($detalle as $item)
                <li>
                    <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-500 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="flex-1 ml-3 text-sm whitespace-nowrap">{{ $item->servicio }}</span>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-md font-extrabold text-white ">${{ $item->costo }}</span>
                    </a>
                </li>
                @endforeach
                @else
                <li>
                    <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-500 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>

                        <span class="flex-1 ml-3 text-xs whitespace-nowrap">No posee servicios adicionales</span>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-lg font-extrabold text-white ">$0.00</span>
                    </a>
                </li>
                @endif
                <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-600 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                    </svg>
                    <span class="flex-1 ml-3 text-lg whitespace-nowrap">Total:</span>
                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-lg font-extrabold text-white ">${{ $total_vista }}</span>
                </a>
                <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-600 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                    </svg>
                    <span class="flex-1 ml-3 text-lg whitespace-nowrap">Total en bolivares:</span>
                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-lg font-extrabold text-white ">Bsd. {{ number_format($total_vista_bsd, 2, ",", ".") }}</span>
                </a>
                </li>
            </ul>
        </div>

        {{-- CAJA --}}
        <div class="w-full max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Método de pago
            </h2>
            <div class="mt-8 space-y-6">
                <div class="px-2">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Forma de pago:</p>
                    <x-select wire:change="$emit('metodo', $event.target.value)" wire:model.live="descripcion" placeholder="Método de pago" :async-data="route('api.metodo_pago_multiple')" option-label="descripcion" option-value="descripcion" />
                </div>
                {{-- <div class="px-2 {{ $ref_hidden }}">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Nro. referencia:</p>
                    <x-input wire:model.defer="referencia" placeholder="12363456" />
                </div> --}}
                <div class="grid grid-cols-2 gap-2 {{ $op1_hidden }}">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago($)</p>
                        <x-select wire:change="$emit('metodo1', $event.target.value)" wire:model.live="op1" placeholder="Seleccione..." :async-data="route('api.metodo_pago_uno')" option-label="descripcion" option-value="descripcion" />
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago(Bs)</p>
                        <x-select wire:change="$emit('metodo2', $event.target.value)" wire:model.live="op2" placeholder="Seleccione..." :async-data="route('api.metodo_pago_dos')" option-label="descripcion" option-value="descripcion" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 {{ $op1_hidden }}">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto en Dolares($)</p>
                        <x-input wire:keydown.enter="calculo($event.target.value)" wire:model.live="valor_uno" value="{{ $valor_uno }}" placeholder="0.00"/>
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto en Bolivares(Bs)</p>
                        <x-input wire:model.live="valor_dos" value="{{ $valor_dos }}" placeholder="0.00" disabled/>
                    </div>
                </div>

                {{-- Referencias --}}
                <div class="grid grid-cols-2 gap-2 {{ $op1_hidden }}">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Referencia($)</p>
                        <x-inputs.maskable wire:model.live="ref_usd" mask="########" placeholder="1236345678"/>
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Referencia(Bs)</p>
                        <x-inputs.maskable wire:model.live="ref_bsd" mask="########" placeholder="1236345678"/>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="px-2">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Propina($)</p>
                        <x-input  wire:model.live="propina_usd" placeholder="0.00"/>
                    </div>
                    <div class="px-2">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Propina(Bs)</p>
                        <x-input wire:model.live="propina_bsd" placeholder="0.00"/>
                    </div>
                </div>
                <div class="px-2 {{ $ref_hidden }}">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Referencia Propina:</p>
                    <x-inputs.maskable wire:model.live="ref_propina" mask="########" placeholder="1236345678"/>
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

