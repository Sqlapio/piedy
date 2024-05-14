<div>
    @livewire('notifications')
    <div class="p-8 bg-[#e9d4cf] rounded-xl mb-5">
        <h1 class="text-xl mb-6 font-bold text-black">MEMBRESIAS</h1>
        <!-- Formulario para la Gifcar -->
        <div class="md:flex justify-between items-center gap-x-8">
            <div class="w-full">
                <div class="w-full h-auto m-auto bg-red-100 rounded-xl relative text-white shadow-2xl transition-transform transform hover:scale-110 ">
                    <img class="object-cover w-full h-full rounded-xl" src="{{ asset('images/membresia-vip-3.png') }}"/>
                </div>
            </div>
            <div class="w-full p-4">
                {{-- linea 1 --}}
                <div class="grid sm:grid-cols-1 md:grid-cols-2 md:gap-6">
                    {{-- <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Código de Membresia</label>
                        <x-input wire:model.live="cod_membresia" right-icon="user" disabled/>
                    </div> --}}
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Cliente</label>
                        <x-select wire:model.live="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
                        <div class="py-2">
                            <x-badge wire:click='nuevo_cliente()' rounded positive label="+ NUEVO CLIENTE" class="py-1 cursor-pointer"/>
                        </div>
                    </div>
                    {{-- Monto --}}
                    @php
                    use App\Models\TasaBcv;
                    $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;
                    @endphp
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Monto($)</label>
                        <x-select placeholder="Monto" :options="[40]" wire:model.live="monto" mask='##'/>
                        @if($metodo_pago == 'Pago Movil' || $metodo_pago == 'Transferencia')
                        <label class="mb-1 block text-sm text-gray-500 text-left">Bs. {{ round($monto * $tasa, 2) }}</label>
                        @endif
                    </div>
                </div>
                {{-- linea 2 --}}
                <div class="grid sm:grid-cols-1 md:grid-cols-2 md:gap-6 mt-5">
                    {{-- Metodo de pago --}}
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Metodo de Pago</label>
                        <x-select placeholder="Método de pago" :options="['Transferencia', 'Pago Movil', 'Zelle']" wire:model.live="metodo_pago"/>
                    </div>
                    {{-- Referencia --}}
                    <div class="w-full mb-6 group">
                        <label class="mb-1 block text-md text-black text-left">Referencia</label>
                        <x-inputs.maskable wire:model="referencia" right-icon="user" mask="########"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex md:justify-end p-2 mt-auto">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Activar Membresia</span>
            </button>
        </div>
    </div>
</div>

