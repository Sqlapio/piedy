<div>
    @livewire('notifications')
    <div class="p-8 bg-[#e9d4cf] rounded-xl mb-5">
        <h1 class="text-xl mb-6 font-bold text-black">Asignación de GiftCard</h1>
        <div class="flex justify-between items-center gap-x-8">
            <div class="w-full">
                <img src="{{ asset('images/gift-card-black.png') }}" alt="">
            </div>
            <div class="w-full">
                {{-- linea 1 --}}
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div class="w-full mb-6 group">
                        <x-input wire:model.live="cod_gift_card" right-icon="user" label="Codigo GiftCard"  disabled/>
                    </div>
                    <div class="w-full mb-6 group">
                        <x-select wire:model="cliente_id" label="Cliente" placeholder="Seleccion" hint="El cliente debe esta registrado" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
                    </div>
                </div>
                {{-- linea 2 --}}
                <div class="grid md:grid-cols-2 md:gap-6 mt-5">
                    <div class="w-full mb-6 group">
                        <x-input wire:model.live="fecha_emicion" right-icon="user" label="Fecha de Emisión" placeholder="Email del cliente"  disabled/>
                    </div>
                    <div class="w-full mb-6 group">
                        <x-inputs.maskable wire:model="monto" label="Monto($)" mask="#### #######" placeholder="Ejemplo: 45 - 50 - 100" />
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
