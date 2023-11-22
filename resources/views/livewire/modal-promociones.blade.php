<div class="flex max-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
    @livewire('notifications')
    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all w-full sm:p-6">
        <div>
            <div class="mx-auto flex w-16 h-auto items-center justify-center">
                <img src="{{ asset('images/icon_promo.png') }}" alt="">
            </div>
            <div class="mt-10 text-center sm:mt-5">
                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Asignación de promoción</h3>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-1 mb-4 mt-5">
                    <div class="p-2">
                        <label class="opacity-60 mb-2 block text-md font-extrabold text-green-700 text-italblue text-left">TÉCNICO</label>
                        <x-select wire:model.defer="empleado_id" placeholder="Seleccion" :async-data="route('api.empleados')" option-label="name" option-value="id" />
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-md font-extrabold text-warning-700 text-italblue text-left">SERVICIOS</label>
                        <x-select wire:model.defer="servicios" placeholder="Seleccion" multiselect :async-data="route('api.promociones_servicios')" option-label="descripcion" option-value="id" />
                    </div>
                </div>
            </div>
        </div>
    <div class="sm:mt-6">
        <button type="button" wire:click="asignar_sevicio()" class="mt-24 inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="asignar_sevicio" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Asignar promoción
        </button>
    </div>
</div>
