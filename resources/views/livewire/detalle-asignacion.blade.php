<div class="w-full max-w-2xl max-h-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 dark:bg-gray-800 dark:border-gray-700">
    @livewire('notifications')
    <h5 class="mb-3 text-base font-semibold text-gray-900 md:text-xl dark:text-white">
        Cliente: Gustavo Camacho
    </h5>
    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Servicio principal</p>
    <ul class="my-4 space-y-3">
        <li>
            <a href="#" class="flex items-center p-4 mb-5 text-base border-none font-bold text-white rounded-lg bg-green-800 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                </svg>

                <span class="flex-1 ml-3 text-sm whitespace-nowrap">{{ $data->servicio }}</span>
                <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-2xl font-extrabold text-white ">${{ $data->costo }}</span>
            </a>
        </li>
            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Servicio adicionales:</p>
            @if(count($detalle) > 0)
                @foreach ($detalle as $item)
                    <li>
                        <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-500 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>                              
                            <span class="flex-1 ml-3 text-sm whitespace-nowrap">{{ $item->servicio }}</span>
                            <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-md font-extrabold text-white ">${{ $item->costo }}</span>
                        </a>
                    </li>
                @endforeach
            @else
                <li>
                    <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-500 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>

                        <span class="flex-1 ml-3 text-xs whitespace-nowrap">No posee servicios adicionales</span>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-lg font-extrabold text-white ">$0.00</span>
                    </a>
                </li>
            @endif
        <li>
            <a href="#" class="flex items-center p-2 px-5 text-base font-bold text-white rounded-lg bg-green-500 shadow-[0px_10px_1px_rgba(221,_221,_221,_1),_0_10px_20px_rgba(204,_204,_204,_1)]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                </svg>
                <span class="flex-1 ml-3 text-lg whitespace-nowrap">Total:</span>
                <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-lg font-extrabold text-white ">${{ $total_vista }}</span>
            </a>
        </li>
    </ul>
    {{-- <div class="mt-10 text-center sm:mt-7">
        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Método de pago</h3>
        <div class="p-2">
            <x-select wire:change="$emit('selected', $event.target.value)" wire:model.defer="descripcion" placeholder="Método de pago" :async-data="route('api.metodo_pago')" option-label="descripcion" option-value="descripcion" />
        </div>
    </div>
    <div class="relative z-0 w-full mb-6 group p-2">
        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Referencia</p>
        <x-input wire:model.defer="referencia" placeholder="12363456" />
    </div> --}}
    <div class="sm:mt-2">
        <button type="button" wire:click="cerrar_servicio()" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-3 mt-10 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Cerrar servicio</button>
    </div>
</div>

