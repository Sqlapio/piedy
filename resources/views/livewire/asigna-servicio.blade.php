<div class="flex max-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
        <div>
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <div class="mt-10 text-center sm:mt-5">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Asignación de servicio</h3>
                <div class="p-2">
                    <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Seleccione un técnico</label>
                    <x-select wire:change="$emit('selected', $event.target.value)" wire:model.defer="empleado_id" placeholder="Seleccion" :async-data="route('api.empleados')" option-label="name" option-value="id" />
                </div>
            </div>
        </div>
    <div class="sm:mt-6">
            <button type="button" wire:click="asignar_tecnico()" class="mt-44 inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">Asignar técnico</button>
    </div>
    <div class="sm:mt-2">
        <button type="button" wire:click="cancel()" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cancelar servicio</button>
    </div>
</div>




