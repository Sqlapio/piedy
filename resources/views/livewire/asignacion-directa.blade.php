<div class="flex max-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-2 sm:max-w-2xl sm:p-6">
        <div>
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="mt-10 text-center sm:mt-5">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Asignación de servicio directa</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4 mb-4 mt-8">
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue text-left">Técnico</label>
                        <x-select wire:model.defer="empleado_id" placeholder="Seleccion" :async-data="route('api.empleados')" option-label="name" option-value="id" />
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue text-left">Cliente</label>
                        <x-select wire:model.defer="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue text-left">Servicio</label>
                        <x-select wire:model.defer="servicio_id" placeholder="Seleccion" :async-data="route('api.servicios')" option-label="descripcion" option-value="id" />
                    </div>
                </div>
            </div>
        </div>
    <div class="sm:mt-6">
            <button type="button" wire:click="asignar_tecnico()" class="mt-44 inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Asignar técnico</button>
    </div>
</div>
