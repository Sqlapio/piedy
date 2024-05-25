<div class="z-index w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
    @livewire('notifications')
    <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
        BCV
    </h5>
    <p class="text-sm mb-2 font-normal text-gray-500 dark:text-gray-400">Costo del Dolar en Bolivares</p>
                <div>
                    <x-inputs.currency
                        prefix="Bs"
                        thousands="."
                        decimal=","
                        precision="4"
                        wire:model="tasa"
                    />
                </div>
    <div class="sm:mt-2">
        <button type="button" wire:click="actualiza_tasa()" class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="actualiza_tasa" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span>Actualizar</span>
        </button>
    </div>
</div>
