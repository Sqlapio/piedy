@php
use App\Models\TasaBcv;
$tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;
@endphp
<div>
    @livewire('notifications')

    @if(Auth::user()->tipo_usuario == 'gerente')

        <section class="renovacion">
            {{-- BOTON RENOVAR MEMBRESIA --}}
            <div class="flex justify-end mt-5 mb-5 {{ $atr_hidden }} {{ $atr_hidden_renovar }}">
                <div wire:click="renovar()" class="cursor-pointer flex items-center border p-4 rounded-xl shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]" style="background-image: url('https://media.istockphoto.com/id/624878906/es/foto/fondo-abstracto-triangular.jpg?b=1&s=170667a&w=0&k=20&c=UTL2PU75t1yyJB_C9ORFOsA7LgkvxIZncxK7A44gGGA='); background-size: cover;">
                    <div class="ml-1 titulos">
                        <div class="text-sm text-black font-bold dark:text-gray-400">RENOVAR MEMBRESIA</div>
                    </div>
                </div>
            </div>

            <h1 class="text-xl mb-6 font-bold text-black {{ $atr_hidden_renovar_form }}">Renovación de Membresia</h1>
        
            {{-- FORMULARIO RENOVAR MEMBRESIA --}}
            <div class="rounded-xl mb-5 {{ $atr_hidden }} {{ $atr_hidden_renovar_form }}">
                <div class="w-full mt-5">
                    {{-- linea 1 --}}
                    <div class="grid sm:grid-cols-1 md:grid-cols-2 sm:gap-6">
                        <div class="w-full group mb-4">
                            <label class="mb-1 block text-xs text-black text-left">Codigo de Membresía(PM)</label>
                            <x-inputs.maskable wire:model.live="cod_pm" right-icon="color-swatch" mask="####"/>
                            <div class="mt-2">
                                <label class="mb-1 block text-xs text-black text-left">Cliente: {{ ($renova_cliente) ? $renova_cliente : '--- ---' }}</label>
                                <label class="mb-1 block text-xs text-black text-left">C.I.: {{ ($renova_ci) ? $renova_ci : '--- ---' }}</label>
                                <label class="mb-1 block text-xs text-black text-left">Email: {{ ($renova_email) ? $renova_email : '---@---' }}</label>
                            </div>
                        </div>
                        <div class="w-full mt-5">
                            <button type="submit" wire:click.prevent="exe_renovacion()" class="w-full rounded-md border border-transparent bg-green-700 py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span>Renovar Membresia</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="registro {{ $atr_hidden_registro }}">
            {{-- Formulario de Membresia --}}
            <div class="p-4 bg-[#e9d4cf] rounded-xl mb-5 {{ $atr_hidden }}">
                <h1 class="text-xl mb-6 font-bold text-black">Asignación de Membresia</h1>
                <!-- Formulario para la Gifcar -->
                <div class="md:flex justify-between items-center gap-x-8">
                    <div class="w-full">
                        <div class="w-full h-auto m-auto bg-red-100 rounded-xl relative text-white shadow-2xl transition-transform transform hover:scale-110 ">

                            <img class="object-cover w-full h-full rounded-xl" src="{{ asset('images/aTARJETA-VIP-2.png') }}" />

                            <div class="w-full px-8 absolute top-12">
                                <div class="flex justify-between">
                                    <div class="lg:mt-10">
                                        <p class="font-light text-black text-xs">
                                            Nombre
                                        </p>
                                        <p class="font-bold text-black">
                                            {{ $cliente }}
                                        </p>
                                    </div>
                                    {{-- <img class="w-1/4 h-auto" src="{{ asset('images/logo.png') }}"/> --}}

                                </div>
                                <div class="pt-1">
                                    <div class="flex justify-between">
                                        <div class="text-xs fond-bold text-black">
                                            Código de Tarjeta
                                        </div>
                                        <div class="text-xs fond-bold tracking-widest text-black">
                                            EXP:{{ date("m/y",strtotime(date('m/y')."+ 6 month")) }}
                                        </div>
                                    </div>
                                    <p class="font-medium tracking-more-wider text-black">
                                        {!! $barcode !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full p-4 mt-5">
                        {{-- linea 1 --}}
                        <div class="grid sm:grid-cols-1 md:grid-cols-2 sm:gap-6">

                            <div class="w-full group mb-4">
                                <label class="mb-1 block text-xs text-black text-left">Cliente(Email)</label>
                                <x-select wire:model.live="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="email" option-value="id" />
                                <div class="py-2">
                                    <x-badge wire:click='nuevo_cliente()' rounded positive label="+ NUEVO CLIENTE" class="py-1 cursor-pointer" />
                                </div>
                            </div>

                            {{-- Monto --}}
                            <div class="w-full group mb-4">
                                <label class="mb-1 block text-xs text-black text-left">Monto: {{ $monto }}$ - Bs.{{ round($monto * $tasa, 2) }}</label>
                                <x-select wire:model.live="monto" placeholder="seleccione el monto" :options="[39.99]" right-icon="currency-dollar" />
                                {{-- <label class="mb-1 block text-sm text-gray-500 text-left">{{ $monto }}$ - Bs.{{ round($monto * $tasa, 2) }}</label> --}}
                            </div>

                            {{-- Metodo de pago --}}
                            <div class="w-full group mb-4">
                                <label class="mb-1 block text-xs text-black text-left">Metodo de Pago</label>
                                <x-select placeholder="Método de pago" :options="['Transferencia', 'Pago Movil', 'Zelle']" wire:model.live="metodo_pago" />
                            </div>

                            {{-- Referencia --}}
                            <div class="w-full group mb-4">
                                <label class="mb-1 block text-xs text-black text-left">Referencia</label>
                                <x-inputs.maskable wire:model="referencia" right-icon="color-swatch" mask="########" />
                            </div>

                            {{-- Campos Ocultos --}}
                            <div class="w-full group hidden">
                                <label class="mb-1 block text-md text-black text-left">Fecha de emición</label>
                                <x-input wire:model.live="fecha_emicion" right-icon="user" disabled />
                            </div>

                            <div class="w-full group hidden">
                                <label class="mb-1 block text-md text-black text-left">Vence</label>
                                <x-input wire:model.live="fecha_vence" right-icon="user" disabled />
                            </div>

                            <div class="w-full mt-5">
                                <button type="submit" wire:click.prevent="store()" class="w-full rounded-md border border-transparent bg-green-700 py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    <span>Activar Membresia</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Formulario Nuevo Cliente --}}
            <div  class="{{ $atr_nuevo_cliente }}">
                <h1 class="text-xl mb-6 font-bold text-[#bd9c95]">FICHA DEL CLIENTE</h1>
                {{-- tabla y boton del formulario de clientes --}}
                <div class="p-5 bg-[#e9d4cf] rounded-xl mb-6">
                    <h1 class="text-xl mb-6 font-bold text-black">Datos Personales</h1>
                    <div class="grid md:grid-cols-3 md:gap-6">
                        <div class="relative z-0 w-full mb-6 group">
                            <x-input wire:model="nombre" right-icon="user" label="Nombre" placeholder="Nombre del cliente" />
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <x-input wire:model="apellido" right-icon="user" label="Apellido" placeholder="Apellido del cliente" />
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <x-inputs.maskable wire:model="cedula" right-icon="user" label="Cédula de Identidad" placeholder="Ejemplo: 16543678" mask="########" />
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <x-input wire:model="email" right-icon="user" label="Email" placeholder="Email del cliente" />
                            {{-- <label for="floating_phone" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label> --}}
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <x-inputs.maskable wire:model="telefono" label="Número Telefónico" mask="#### #######" placeholder="Número telefónico" />
                            {{-- <label for="floating_company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Telefono</label> --}}
                        </div>
                    </div>
        
                    {{-- Boton agregar nuevo cliente --}}
                    <div class="flex justify-end p-2 mt-auto gap-5">
                        <div>
                            <button type="submit" wire:click.prevent="regresar()" class="rounded-md border border-transparent bg-red-700 py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="regresar      " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span>Regresar</span>
                            </button>
                        </div>
                        <div>
                            <button type="submit" wire:click.prevent="store_nuevo_cliente()" class="justify-end rounded-md border border-transparent bg-green-700 py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store_nuevo_cliente" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span>Registrar Cliente</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tabla">
            {{-- Tabla de membrtesias --}}
            <div class="border rounded-lg mb-5">
                <p class="p-4 text-xl font-bold text-[#bc9c95]">MEMBRESIAS REGISTRADAS</p>
                @livewire('tabla-membresia')
            </div>
        </section>

    @else

        <section class="renovacion tabla">
            {{-- BOTON RENOVAR MEMBRESIA --}}
            <div class="flex justify-end mt-5 mb-5">
                <div wire:click="renovar()" class="cursor-pointer flex items-center border p-4 rounded-xl shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]" style="background-image: url('https://media.istockphoto.com/id/624878906/es/foto/fondo-abstracto-triangular.jpg?b=1&s=170667a&w=0&k=20&c=UTL2PU75t1yyJB_C9ORFOsA7LgkvxIZncxK7A44gGGA='); background-size: cover;">
                    <div class="ml-1 titulos">
                        <div class="text-sm text-black font-bold dark:text-gray-400">RENOVAR MEMBRESIA</div>
                    </div>
                </div>
            </div>
            {{-- FORMULARIO RENOVAR MEMBRESIA --}}
            <div class="p-4 rounded-xl mb-5">
                <h1 class="text-xl mb-6 font-bold text-black">Renovación de Membresia</h1>
                <div class="w-full p-4 mt-5">
                    {{-- linea 1 --}}
                    <div class="grid sm:grid-cols-1 md:grid-cols-2 sm:gap-6">
                        <div class="w-full group mb-4">
                            <label class="mb-1 block text-xs text-black text-left">Codigo de Membresía(PM)</label>
                            <x-inputs.maskable wire:model.live="cod_pm" right-icon="color-swatch" mask="####"/>
                            <div class="mt-2">
                                <label class="mb-1 block text-xs text-black text-left">Cliente: {{ ($renova_cliente) ? $renova_cliente : '--- ---' }}</label>
                                <label class="mb-1 block text-xs text-black text-left">C.I.: {{ ($renova_ci) ? $renova_ci : '--- ---' }}</label>
                                <label class="mb-1 block text-xs text-black text-left">Email: {{ ($renova_email) ? $renova_email : '---@---' }}</label>
                            </div>
                        </div>
                        <div class="w-full mt-5">
                            <button type="submit" wire:click.prevent="exe_renovacion()" class="w-full rounded-md border border-transparent bg-green-700 py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span>Renovar Membresia</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Tabla de membrtesias --}}
            <div class="border rounded-lg mb-5">
                <p class="p-4 text-xl font-bold text-[#bc9c95]">MEMBRESIAS REGISTRADAS</p>
                @livewire('tabla-membresia')
            </div>
        </section>
        
    @endif

    {{-- div para separacion --}}
    <div class="w-full h-28"></div>

    {{-- Menu para table --}}
    <div class="fixed sm:z-0 md:z-0 lg:z-50 w-full h-16 mt-5 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-7 mx-auto ">
            <button data-tooltip-target="tooltip-home" type="button" wire:click="inicio" class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                <span class="sr-only">Inicio</span>
            </button>
            <div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Inicio
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-wallet" type="button" wire:click="productos" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                    <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                </svg>
                <span class="sr-only">Productos</span>
            </button>
            <div id="tooltip-wallet" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Productos
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-products" type="button" wire:click="cabinas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 0H1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1Zm14 0h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1ZM5 14H1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1Zm14 0h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1ZM12 2H8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2Zm0 14H8a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2Zm-8-4V8a1 1 0 0 0-2 0v4a1 1 0 1 0 2 0Zm14 0V8a1 1 0 0 0-2 0v4a1 1 0 0 0 2 0Z"/>
                </svg>
                <span class="sr-only">Cabinas</span>
            </button>
            <div id="tooltip-products" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Cabinas
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <div class="flex items-center justify-center ">
                <button data-tooltip-target="tooltip-new" type="button" wire:click.prevent="regresar" class="inline-flex items-center justify-center w-10 h-10 font-medium bg-[#7898a5] rounded-full hover:bg-[#5390a7] group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 shadow-[0_2.8px_2.2px_rgba(0,_0,_0,_0.034),_0_6.7px_5.3px_rgba(0,_0,_0,_0.048),_0_12.5px_10px_rgba(0,_0,_0,_0.06),_0_22.3px_17.9px_rgba(0,_0,_0,_0.072),_0_41.8px_33.4px_rgba(0,_0,_0,_0.086),_0_100px_80px_rgba(0,_0,_0,_0.12)]">
                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 16">
                        <path d="M12.5 3.046H10v-.928A2.12 2.12 0 0 0 8.8.164a1.828 1.828 0 0 0-1.985.311l-5.109 4.49a2.2 2.2 0 0 0 0 3.24L6.815 12.7a1.83 1.83 0 0 0 1.986.31A2.122 2.122 0 0 0 10 11.051v-.928h1a2.026 2.026 0 0 1 2 2.047V15a.999.999 0 0 0 1.276.961A6.593 6.593 0 0 0 12.5 3.046Z"/>
                    </svg>
                    <span class="sr-only">Atras</span>
                </button>
            </div>
            <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Atras
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-settings" type="button"  wire:click="citas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path fill="currentColor" d="M6 1a1 1 0 0 0-2 0h2ZM4 4a1 1 0 0 0 2 0H4Zm7-3a1 1 0 1 0-2 0h2ZM9 4a1 1 0 1 0 2 0H9Zm7-3a1 1 0 1 0-2 0h2Zm-2 3a1 1 0 1 0 2 0h-2ZM1 6a1 1 0 0 0 0 2V6Zm18 2a1 1 0 1 0 0-2v2ZM5 11v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 11v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 15v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 15v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 11v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM5 15v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM2 4h16V2H2v2Zm16 0h2a2 2 0 0 0-2-2v2Zm0 0v14h2V4h-2Zm0 14v2a2 2 0 0 0 2-2h-2Zm0 0H2v2h16v-2ZM2 18H0a2 2 0 0 0 2 2v-2Zm0 0V4H0v14h2ZM2 4V2a2 2 0 0 0-2 2h2Zm2-3v3h2V1H4Zm5 0v3h2V1H9Zm5 0v3h2V1h-2ZM1 8h18V6H1v2Zm3 3v.01h2V11H4Zm1 1.01h.01v-2H5v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H5v2h.01v-2ZM9 11v.01h2V11H9Zm1 1.01h.01v-2H10v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM9 15v.01h2V15H9Zm1 1.01h.01v-2H10v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM14 15v.01h2V15h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM14 11v.01h2V11h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM4 15v.01h2V15H4Zm1 1.01h.01v-2H5v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H5v2h.01v-2Z"/>
                </svg>
                <span class="sr-only">Agenda</span>
            </button>
            <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Agenda
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-services" type="button"  wire:click="servicios" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M17 11h-2.722L8 17.278a5.512 5.512 0 0 1-.9.722H17a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM6 0H1a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V1a1 1 0 0 0-1-1ZM3.5 15.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM16.132 4.9 12.6 1.368a1 1 0 0 0-1.414 0L9 3.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z"/>
                </svg>
                <span class="sr-only">Servicios</span>
            </button>
            <div id="tooltip-services" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Servicios
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-profile" type="button"  wire:click="clientes"  class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                </svg>
                <span class="sr-only">Clientes</span>
            </button>
            <div id="tooltip-profile" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Clientes
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</div>

