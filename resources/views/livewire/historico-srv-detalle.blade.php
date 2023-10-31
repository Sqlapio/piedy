
<div class="w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
    @livewire('notifications')
    <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
        Detalles
    </h5>
    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Asociados al servicio selecionado</p>
    <ul class="my-4 space-y-3 overflow-auto">
        @foreach ($data->detalle_asignacions as $item)
            <li>
                <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-500 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                    </svg>
                    <span class="flex-1 ml-3 text-sm whitespace-nowrap">{{ $item->servicio }}</span>
                    <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-md font-extrabold text-white ">${{ $item->costo }}</span>
                </a>
            </li>
        @endforeach
    </ul>
    <div class="sm:mt-2">
        <button type="button" wire:click="cerrar_modal" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cerrar</button>
    </div>
</div>


  
