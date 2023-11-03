@php
use App\Models\TasaBcv as ModelsTasaBcv;
    $tasa = ModelsTasaBcv::first()->tasa;
@endphp
<div>
    <div class="grid grid-cols-2 gap-2">
        <div class="w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            @livewire('notifications')
            <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
                Cliente: {{ $data->cliente }}
            </h5>

            <ul class="my-4 space-y-3">
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
                        <svg wire:click="eliminar_servicio({{ $item->id }})" class="w-4 h-4 ml-2 text-red-600 cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                        </svg>
                        
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
        <div class="w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Método de pago
            </h2>
            <div class="mt-8 space-y-6">
                <div class="px-2">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Forma de pago:</p>
                    {{-- <x-native-select
    label="Select Status"
    :options="[
        ['metodo' => '',  'id' => 1],
        ['metodo' => 'Pago ', 'id' => 2],
        ['metodo' => 'Stuck',   'id' => 3],
        ['metodo' => 'Done',    'id' => 4],
        ['metodo' => 'Stuck',   'id' => 3],
        ['metodo' => 'Stuck',   'id' => 3],
        ['metodo' => 'Stuck',   'id' => 3],
        ['metodo' => 'Stuck',   'id' => 3],
        ['metodo' => 'Stuck',   'id' => 3],
        ['metodo' => 'Stuck',   'id' => 3],
    ]"
    option-label="metodo"
    option-value="id"
    wire:model="model"
/> --}}
                    <x-select wire:change="$emit('metodo', $event.target.value)" wire:model.live="descripcion" placeholder="Método de pago" :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
                </div>
                <div class="px-2 {{ $ref_hidden }}">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Nro. referencia:</p>
                    <x-input wire:model.defer="referencia" placeholder="12363456" />
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método 1</p>
                        <x-select wire:change="$emit('metodo1', $event.target.value)" wire:model.live="op1" placeholder="Método 1" :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método 2</p>
                        <x-select wire:change="$emit('metodo2', $event.target.value)" wire:model.live="op2" placeholder="Método 2" :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto</p>
                        <x-input wire:keydown.enter="calculo($event.target.value)" wire:model.live="valor_uno" value="{{ $valor_uno }}" placeholder="0.00"/>
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto restante</p>
                        <x-input wire:model.live="valor_dos" value="{{ $valor_dos }}" placeholder="0.00"/>
                    </div>
                </div>
                <div class="sm:mt-2">
                    <button type="button" wire:click="facturar_servicio()" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Facturar servicio</button>
                </div>
            </div>
        </div>
    </div>
    <x-menu_table />
</div>
