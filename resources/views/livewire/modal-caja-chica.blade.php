<div class="z-index w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
        PIEDY CAJA CHICA
    </h5>
    <p class="text-sm mb-2 font-normal text-gray-500 dark:text-gray-400">Monto inicial</p>
                <div>
                    <x-inputs.currency
                        prefix="$"
                        thousands="."
                        decimal=","
                        precision="4"
                        wire:model="monto"
                    />
                </div>
    <div class="sm:mt-2">
        <button type="button" wire:click="actualiza_caja_chica()" class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Actualizar</button>
    </div>
</div>
