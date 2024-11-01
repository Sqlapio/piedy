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
            @livewire('table-detalle-asignacion',
                        [
                            'cod_asignacion' => $factura->cod_asignacion,
                            'cliente_id'     => $factura->cliente_id
                        ])
        </div>

        {{-- CAJA --}}
        <div class="w-full max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Método de pago
            </h2>
            <div class="mt-8 space-y-6">
                {{-- Metodo de pago Prepagado --}}
                <div class="grid grid-cols-1 gap-2">
                    <div class="px-2">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Metodo de pago Prepagado:</p>
                        <x-select   class="{{ ($metodo_pago_pre == 'Giftcard') ? 'border rounded-md border-green-400' : '' }}"
                                    placeholder="Select one status"
                                    :options="[
                                        ['name' => 'Giftcard',  'id' => 1],
                                        ['name' => 'Seguro - TuDr.EnCasa', 'id' => 2],
                                        ]"
                                    option-label="name"
                                    option-value="name"
                                    wire:model.live="metodo_pago_pre"
                                    />
                    </div>
                </div>

                {{-- Membresia --}}
                {{-- <div class="grid grid-cols-1 gap-2 {{ $atr_mem }}">
                    <div class="px-2">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Codigo de Membresia</p>
                        <x-inputs.maskable wire:model.live="codigo_mem" wire:keydown.enter="valida_membresia($event.target.value)" mask="####" placeholder="4563"/>
                        @if (session('activa'))
                            <div class="flex justify-start alert alert-success text-xs text-green-800 font-bold text-left px-2">
                                <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/checkmark.gif') }}" alt="">
                                <div class="py-2">
                                    {{ session('activa') }}
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="flex justify-start alert alert-success text-xs text-red-800 font-bold text-left px-2">
                                <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/cancel.gif') }}" alt="">
                                <div class="py-2">
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div> --}}


                {{-- Nro. de GiftCard y Monto --}}
                <div class="grid grid-cols-2 gap-2 {{ $atr_giftCard }}">
                    <div class="px-2">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">CVC</p>
                        <x-inputs.maskable wire:model.live="codigo" wire:keydown.enter="valida_giftcard($event.target.value)" mask="####" placeholder="4563"/>
                        @if (session('activa'))
                            <div class="flex justify-start alert alert-success text-xs text-green-800 font-bold text-left px-2">
                                <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/checkmark.gif') }}" alt="">
                                <div class="py-2">
                                    {{ session('activa') }}
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="flex justify-start alert alert-success text-xs text-red-800 font-bold text-left px-2">
                                <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/cancel.gif') }}" alt="">
                                <div class="py-2">
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="px-2">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto($)</p>
                        <x-input wire:model.live="monto_giftcard" value="{{ $monto_giftcard }}" wire:keydown.enter="calculo($event.target.value)" disabled/>
                    </div>
                </div>

                {{-- Metodos de pago --}}
                <div class="grid grid-cols-2 gap-2 {{ $op1_hidden }}">
                    <div class="px-2 {{ $op1_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago($)</p>
                        <x-select class="{{ ($op1 == 'Efectivo Usd' || $op1 == 'Zelle') ? 'border rounded-md border-green-400' : '' }}" wire:change="$emit('metodo1', $event.target.value)" wire:model.live="op1" placeholder="Seleccione..." :async-data="route('api.metodo_pago_uno')" option-label="descripcion" option-value="descripcion" />
                    </div>
                    <div class="px-2 {{ $op2_hidden }}">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago(Bs)</p>
                        <x-select class="{{ ($op2 == 'Efectivo Bsd' || $op2 == 'Pago movil' || $op2 == 'Punto de venta' || $op2 == 'Transferencia') ? 'border rounded-md border-orange-400' : '' }}" wire:change="$emit('metodo2', $event.target.value)" wire:model.live="op2" placeholder="Seleccione..." :async-data="route('api.metodo_pago_dos')" option-label="descripcion" option-value="descripcion" />
                    </div>
                </div>

                {{-- Montos en dolares o Bolivares --}}
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

