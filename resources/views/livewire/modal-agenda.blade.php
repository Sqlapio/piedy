
<div class="z-index w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
     <div class="flex justify-between mb-5">
        <div class="flex">
            <h1 class="mb-5 text-base font-semibold text-[#bd9c95] md:text-xl dark:text-white">
                Piedy Agenda
            </h1>
        </div>
        <div class="flex">
            <h1 class="mb-5 text-base font-semibold text-[#bd9c95] md:text-xl dark:text-white">
                {{ $dia }}
            </h1>
        </div>
     </div>
     <div class="{{ $ocultar }} p-2">
        <div class="flex justify-between">
            <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Cliente</label>
            <label wire:click="mostrar_nuevo"class="mb-1 block text-MD font-medium text-green-600 cursor-pointer">NUEVO CLIENTE</label>
        </div>
        <x-select wire:model="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
    </div>
    <div class="{{ $nuevo }} p-2">
        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Nuevo cliente</label>
        <x-input right-icon="user" wire:model="cliente" />
    </div>
    <div class="{{ $nuevo }} p-2">
        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Correo Electrónico</label>
        <x-input right-icon="user" wire:model="correo" type="email" />
    </div>
    <div class="{{ $nuevo }} p-2">
        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Teléfono</label>
        <x-input right-icon="user" wire:model="telefono" type="email" />
    </div>
    <div class="p-2">
        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Hora</label>
        <x-time-picker placeholder="hora" format="12" interval="30" wire:model="hora" />
    </div>

    <div class="sm:mt-2">
        <button type="button" wire:click.prevent="store()" class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span>Agendar Cita</span>
        </button>
    </div>
</div>
