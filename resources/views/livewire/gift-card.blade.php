<div>
    @livewire('notifications')
    <div class="p-8 bg-[#e9d4cf] rounded-xl mb-5">
        <h1 class="text-xl mb-6 font-bold text-black">Asignación de GiftCard</h1>
        <!-- Formulario para la Gifcar -->
        <div class="flex justify-between items-center gap-x-8">
            <div class="w-full">
                <div class="w-full h-auto m-auto bg-red-100 rounded-xl relative text-white shadow-2xl transition-transform transform hover:scale-110 ">

                    <img class="object-cover w-full h-full rounded-xl" src="{{ asset('images/gift-card1.png') }}"/>

                    <div class="w-full px-8 absolute top-12">
                        <div class="flex justify-between">
                            <div class="">
                                <p class="font-light text-black text-xs">
                                    Nombre
                                </p>
                                <p class="font-medium tracking-widest text-black">
                                    {{ $cliente }}
                                </p>
                            </div>
                            {{-- <img class="w-1/4 h-auto" src="{{ asset('images/logo.png') }}"/> --}}

                        </div>
                        <div class="pt-1">
                            <p class="font-light text-black text-xs py-1">
                                Código de Tarjeta
                            </p>

                            <p class="font-medium tracking-more-wider text-black">
                                {!! $barcode !!}
                            </p>
                        </div>
                        <div class="pt-2 pr-6">
                            <div class="flex justify-between">
                                <div class="">
                                    <p class="font-light text-xs text-black">
                                        Valida
                                    </p>
                                    <p class="font-bold tracking-more-wider text-sm text-black">
                                        {{ date('m/y') }}
                                    </p>
                                </div>
                                <div class="">
                                    <p class="font-light text-xs text-black">
                                        Expira
                                    </p>
                                    <p class="font-bold tracking-more-wider text-sm text-black">
                                        {{ date("m/y",strtotime(date('m/y')."+ 6 month")) }}
                                    </p>
                                </div>

                                <div class="">
                                    <p class="font-light text-xs text-black">
                                        PGC
                                    </p>
                                    <p wire:model.live='pgc' class="font-bold tracking-more-wider text-sm text-black">
                                        {{ $pgc }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="w-full p-4">
                {{-- linea 1 --}}
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Código GiftCard</label>
                        <x-input wire:model.live="cod_gift_card" right-icon="user" disabled/>
                    </div>
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Cliente</label>
                        <x-select wire:model.live="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
                        <div class="py-2">
                            <x-badge wire:click='nuevo_cliente()' rounded positive label="+ NUEVO CLIENTE" class="py-1 cursor-pointer"/>
                        </div>
                    </div>
                </div>
                {{-- linea 2 --}}
                <div class="grid md:grid-cols-3 md:gap-6 mt-5">
                    <div class="w-full mb-6 group hidden">
                        <label class="mb-1 block text-md text-black text-left">Fecha de emición</label>
                        <x-input wire:model.live="fecha_emicion" right-icon="user" disabled/>
                    </div>
                    <div class="w-full mb-6 group hidden">
                        <label class="mb-1 block text-md text-black text-left">Vence</label>
                        <x-input wire:model.live="fecha_vence" right-icon="user" disabled/>
                    </div>
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Monto ($)</label>
                        <x-select placeholder="Monto" :options="[20, 40]" wire:model.live="monto" mask='##' hint="{{ ($metodo_pago == 'Pago Movil' || $metodo_pago == 'Punto de Venta') ? 'Bs. '.$monto = $monto * $tasa : $monto }}"/>
                    </div>
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Metodo de Pago</label>
                        <x-select placeholder="Método de pago" :options="['Efectivo USD', 'Pago Movil', 'Zelle', 'Punto de Venta']" wire:model.live="metodo_pago"/>
                    </div>
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Referencia</label>
                        <x-input wire:model="referencia" right-icon="user"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end p-2 mt-auto">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Asignar GiftCard</span>
            </button>
        </div>
    </div>
</div>
