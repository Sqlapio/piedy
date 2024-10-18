<div>
    <div class="">
        @livewire('notifications')
        <div class="flex justify-between items-center gap-2 p-4">
            <div class="font-medium dark:text-white">
                <div class="text-md text-black font-extrabold dark:text-gray-400">Codigo: {{ $codigoAsignacion }}</div>
            </div>
            <div class="font-medium ">
                @if($hidden == '')
                <svg wire:click="updateProperty" class="w-[30px] h-[30px] text-[#16a34a] cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                </svg>
                @else
                <svg wire:click="updateProperty" class="w-[30px] h-[30px] text-[#dc2626] cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                  </svg>

                @endif

            </div>
        </div>
        <div class="grid grid-cols-1 gap-2 p-4 {{ $tableProductos }}">
            @livewire('table-producto')
        </div>
        <div class="grid grid-cols-5 gap-2 mb-10 p-2">
            {{-- servicio asignado --}}
            <div class="w-full col-span-3">
                @livewire('table-pre-select-pro')
            </div>

            {{-- Seleccion de proructos --}}
            {{-- CAJA --}}
            <div class="p-2 col-span-2">
                <div class="w-full max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Método de pago
                    </h2>
                    <div class="mt-8 space-y-6">
                        {{-- Metodo de pago Prepagado --}}
                        {{-- <div class="grid grid-cols-1 gap-2">
                            <div class="px-2">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Metodo de pago Prepagado:</p>
                                <x-select class="" placeholder="Select one status"
                                :options="[
                                    ['name' => 'Giftcard',  'id' => 1],
                                ]" option-label="name" option-value="name" wire:model.live="metodo_pago_pre" />

                            </div>
                        </div> --}}

                        <!-- Metodfos de pago -->
                        <div class="grid grid-cols-2 gap-2 ">
                            <div class="px-2 ">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago($)</p>
                                <x-select class="" wire:model.live="metodoUsd" placeholder="Seleccione..." :async-data="route('api.metodo_pago_uno')" option-label="descripcion" option-value="descripcion" />
                            </div>
                            <div class="px-2 ">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Método de pago(Bs)</p>
                                <x-select class="" wire:model.live="metodoBsd" placeholder="Seleccione..." :async-data="route('api.metodo_pago_dos')" option-label="descripcion" option-value="descripcion" />
                            </div>
                        </div>

                        <!-- Monto Dolares y Bolivares -->
                        <div class="grid grid-cols-2 gap-2 {{ ($metodoUsd != '' || $metodoBsd != '') ? 'block' : 'hidden' }}">
                            <div class="px-2 ">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Monto en Dolares($)</p>
                                {{-- <x-inputs.maskable wire:model.live="ref_usd" mask="" placeholder="1236345678"/> --}}
                                {{-- <x-input wire:keydown.enter="calculo($event.target.value)" wire:model.live="montoUsd" value="" placeholder="$0.00"/> --}}
                                <x-inputs.currency icon="currency-dollar" wire:keydown.enter="calculo($event.target.value)" wire:model.live="montoUsd" value="" placeholder="0.00" />
                            </div>
                            <div class="px-2 ">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Monto en Bolivares(Bs)</p>
                                <x-input icon="calculator" wire:model.live="montoBsd" value="{{ $montoBsd }}" placeholder="0.00" disabled/>
                            </div>
                        </div>

                        <!-- Referencia -->
                        <div class="grid {{  ($metodoUsd == 'Zelle' && $metodoBsd == 'Pago movil' || $metodoBsd == 'Transferencia' || $metodoBsd == 'Punto de venta' && $metodoBsd == 'Punto de venta') ? 'grid-cols-3' : 'grid-cols-2'  }} gap-2">
                            <div class="px-2 {{ ($metodoUsd == 'Zelle') ? 'block' : 'hidden' }}">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Referencia($)</p>
                                <x-inputs.maskable wire:model.live="referenciaUsd" mask="####" placeholder="1236"/>

                            </div>
                            <div class="px-2 {{ ($metodoBsd == 'Pago movil' || $metodoBsd == 'Transferencia' || $metodoBsd == 'Punto de venta') ? 'block' : 'hidden' }}">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">Referencia(Bs)</p>
                                <x-inputs.maskable wire:model.live="referenciaBsd" mask="####" placeholder="5678"/>

                            </div>
                            <div class="px-2 {{ ($metodoBsd == 'Punto de venta') ? 'block' : 'hidden' }}">
                                <p class="text-sm font-normal text-gray-500 dark:text-gray-400 ">#Tarjeta</p>
                                <x-inputs.maskable wire:model.live="nroTarjeta" mask="####" placeholder="3456"/>

                            </div>

                        </div>

                        <!-- Boton -->
                        <div class="sm:mt-2">
                            <button type="button"  wire:click="facturar_producto" class="inline-flex w-full justify-center rounded-lg bg-[#16a34a] px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-[#0e5528] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="facturar_producto" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span>Facturar Producto</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="border rounded-lg mb-5 mt-5 hidden">
            <p class="p-4 text-3xl font-bold text-[#bc9c95]">Venta de Productos</p>
            @livewire('VentaProducto.table-venta-producto')
        </div>
    </div>


</div>
