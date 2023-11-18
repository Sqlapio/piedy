<div class="flex max-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
    @livewire('notifications')
    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all w-full sm:p-6">
        <div>
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <svg class="w-6 h-6 text-green-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M18.017 15.002h-1.5v-1.5a1 1 0 0 0-2 0v1.5h-1.5a1 1 0 0 0 0 2h1.5v1.5a1 1 0 1 0 2 0v-1.5h1.5a1 1 0 1 0 0-2Z"/>
                    <path d="m17.74 4.758-7.476 8.409a1 1 0 0 1-.718.335h-.029a1 1 0 0 1-.707-.293l-4-4a1 1 0 0 1 1.414-1.413l3.25 3.25L16.53 3.11a9.5 9.5 0 1 0-3.885 15.355 2.495 2.495 0 0 1 .373-4.963 2.5 2.5 0 0 1 5 0c.035 0 .068.01.1.01a9.43 9.43 0 0 0-.38-8.754h.002Z"/>
                  </svg>

            </div>
            <div class="mt-10 text-center sm:mt-5">
                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Asignación de servicio directa</h3>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-1 mb-4 mt-5">
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-md font-extrabold text-green-700 text-italblue text-left">TÉCNICO</label>
                        <x-select wire:model.defer="empleado_id" placeholder="Seleccion" :async-data="route('api.empleados')" option-label="name" option-value="id" />
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-md font-extrabold text-amber-700 text-italblue text-left">CLIENTE</label>
                        <x-select wire:model.defer="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-md font-extrabold text-blue-700 text-italblue text-left">SERVICIO</label>
                        <x-select wire:model.defer="servicio_id" placeholder="Seleccion" :async-data="route('api.servicios')" option-label="descripcion" option-value="id" />
                    </div>
                </div>
            </div>
        </div>
    <div class="sm:mt-6">
        <button type="button" wire:click="asignar_tecnico()" class="mt-20 inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Asignar técnico</button>
    </div>
</div>
