
<div class="z-index w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
     <div class="flex flex-col mb-2">
        <div class="flex">
            <h1 class="mb-1 text-base font-semibold text-[#bd9c95] md:text-xl dark:text-white">
                Asignación de servicio
            </h1>
        </div>
        <div class="flex">
            <h1 class="mb-1 text-base font-semibold text-[#bd9c95] md:text-sm dark:text-white">
                Cliente: {{$cita->cliente}}
            </h1>
        </div>
     </div>
    <div class="p-2">
        <label class="opacity-60 mb-1 block text-md font-extrabold text-blue-700 text-italblue text-left">SERVICIO</label>
        <x-select wire:model.defer="servicio_id" placeholder="Seleccion" :async-data="route('api.servicios')" option-label="descripcion" option-value="id" />
    </div>
    <div class="p-2">
        <label class="opacity-60 mb-1 block text-md font-extrabold text-green-700 text-italblue text-left">TÉCNICO</label>
        <x-select wire:model.defer="empleado_id" placeholder="Seleccion" :async-data="route('api.empleados')" option-label="name" option-value="id" />
    </div>


    <div class="sm:mt-2">
        <button type="button" wire:click="asignar_tecnico()" class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-3 mt-16 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span>Asignar servicio</span>
        </button>
    </div>
</div>
