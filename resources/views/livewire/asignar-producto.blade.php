<div>
    <div class="p-5">
        <h1 class="text-xl mb-6 font-bold uppercase text-[#bd9c95]">Asignar Productos</h1>
        {{-- tabla y boton del formulario de clientes --}}
        <div class="">
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="p-2">
                    <label class="opacity-60 mb-1 block text-md font-extrabold text-amber-700 text-italblue text-left">PRODUCTO</label>
                    <x-select wire:model.defer="producto" placeholder="Seleccion" :async-data="route('api.lista_productos')" option-label="descripcion" option-value="id" />
                </div>
                <div class="p-2">
                    <label class="opacity-60 mb-1 block text-md font-extrabold text-blue-700 text-italblue text-left">EMPLEADO</label>
                    <x-select wire:model.defer="empleado" placeholder="Seleccion" :async-data="route('api.empleados')" option-label="name" option-value="id" />
                </div>
                <div class="p-2">
                    <label class="opacity-60 mb-1 block text-md font-extrabold text-blue-700 text-italblue text-left">CANTIDAD A ENTREGAR</label>
                    <x-input wire:model="cantidad" right-icon="pencil" placeholder="Númros enteros: 10 - 20 - 100" />
                </div>
            </div>
            <div class="flex justify-end p-2 mt-auto mb-52">
                <button type="submit" wire:click.prevent="asignar()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Asignar Producto</span>
                </button>
            </div>
        </div>
    </div>
</div>

