<div>
    @livewire('notifications')
    <div class="px-10 py-4">
        <div class="mb-10">
            <div class="flex">
                <h1 class="text-xl mb-6 font-extrabold text-[#7898a5] uppercase">MODULO DE NOMINA QUIROPEDISTAS</h1>
            </div>
            <div class="flex">
                <h1 class="text-sm mb-4 font-extrabold text-[#bd9c95] uppercase">Rango de Fecha</h1>
            </div>
            <div class="grid md:grid-cols-4 sm:gap-4 md:gap-4">
                <div>
                    <x-datetime-picker id="min-max-times-input" without-timezone label="Desde:" placeholder="desde" wire:model.defer="desde" min-time="05:00" max-time="23:00" parse-format="YYYY-MM-DD HH:mm:ss" />
                </div>
                <div>
                    <x-datetime-picker id="min-max-times-input" without-timezone label="Hasta:" placeholder="hasta" wire:model.defer="hasta" min-time="05:00" max-time="23:00" parse-format="YYYY-MM-DD HH:mm:ss" />
                </div>
                <div>
                    <x-select label="Select Status" placeholder="Selccione quincena"
                        :options="[
                            ['descripcion' => '1era. Quincena',  'id' => 'primera'],
                            ['descripcion' => '2da. Quincena', 'id' => 'segunda'],
                        ]"
                        option-label="descripcion"
                        option-value="id"
                        wire:model.defer="quincena"
                    />
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-between">
                <div>
                    <h1 class="text-sm mb-6 font-extrabold text-[#bd9c95] uppercase">Tabla de asignaciones y deducciones</h1>
                </div>
                <div>
                    <x-button icon="check" positive label="cargar nomina" wire:click="store" class="uppercase text-xs font-extrabold" />
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)] sm:rounded-lg">
                <table class="w-full text-sm text-center">
                    <thead class="text-xs text-black font-extrabold uppercase bg-[#bc9b94]">
                        <tr>
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Quiropedista
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Asignaciones(Bs.)
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Deducciones($)
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Deducciones(Bs.)
                            </th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($data); $i++)
                            <tr class="bg-[#e4dcdc] border-b border-white">
                                <th scope="row" class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-white">
                                    {{ $data[$i]->name }}
                                </th>
                                <td class="px-6 py-4">
                                    <div class="">
                                        <x-input right-icon="calculator" wire:change="conver_asignacion_bolivares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="asignacion_bolivares.{{ $data[$i]->id }}" type="email" />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="">
                                        <x-input right-icon="calculator" wire:change="conver_deduccion_dolares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="deduccion_dolares.{{ $data[$i]->id }}" type="email" />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="">
                                        <x-input right-icon="calculator" wire:change="conver_deduccion_bolivares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="deduccion_bolivares.{{ $data[$i]->id }}" type="email" />
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="px-10 py-4 mt-4">
        <div class="flex">
            <h1 class="text-sm mb-4 font-bold text-[#bd9c95] uppercase">tabla de nomina</h1>
        </div>
        @livewire('table-nom-quiropedista')
    </div>

    {{-- div para separacion --}}
    <div class="w-full h-28"></div>

    {{-- Menu para table --}}
    <div class="fixed sm:z-0 md:z-0 lg:z-50 w-full h-16 mt-5 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-7 mx-auto ">
            <button data-tooltip-target="tooltip-home" type="button" wire:click="accion({{ 1 }})" class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-7 h-7 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M20.29 8.567c.133.323.334.613.59.85v.002a3.536 3.536 0 0 1 0 5.166 2.442 2.442 0 0 0-.776 1.868 3.534 3.534 0 0 1-3.651 3.653 2.483 2.483 0 0 0-1.87.776 3.537 3.537 0 0 1-5.164 0 2.44 2.44 0 0 0-1.87-.776 3.533 3.533 0 0 1-3.653-3.654 2.44 2.44 0 0 0-.775-1.868 3.537 3.537 0 0 1 0-5.166 2.44 2.44 0 0 0 .775-1.87 3.55 3.55 0 0 1 1.033-2.62 3.594 3.594 0 0 1 2.62-1.032 2.401 2.401 0 0 0 1.87-.775 3.535 3.535 0 0 1 5.165 0 2.444 2.444 0 0 0 1.869.775 3.532 3.532 0 0 1 3.652 3.652c-.012.35.051.697.184 1.02ZM9.927 7.371a1 1 0 1 0 0 2h.01a1 1 0 0 0 0-2h-.01Zm5.889 2.226a1 1 0 0 0-1.414-1.415L8.184 14.4a1 1 0 0 0 1.414 1.414l6.218-6.217Zm-2.79 5.028a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01Z" clip-rule="evenodd"/>
                  </svg>

                <span class="sr-only">Nomina Quiropedista</span>
            </button>
            <div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Nomina Quiropedista
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <button data-tooltip-target="tooltip-wallet" type="button" wire:click="accion({{ 2 }})" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-7 h-7 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.891 15.107 15.11 8.89m-5.183-.52h.01m3.089 7.254h.01M14.08 3.902a2.849 2.849 0 0 0 2.176.902 2.845 2.845 0 0 1 2.94 2.94 2.849 2.849 0 0 0 .901 2.176 2.847 2.847 0 0 1 0 4.16 2.848 2.848 0 0 0-.901 2.175 2.843 2.843 0 0 1-2.94 2.94 2.848 2.848 0 0 0-2.176.902 2.847 2.847 0 0 1-4.16 0 2.85 2.85 0 0 0-2.176-.902 2.845 2.845 0 0 1-2.94-2.94 2.848 2.848 0 0 0-.901-2.176 2.848 2.848 0 0 1 0-4.16 2.849 2.849 0 0 0 .901-2.176 2.845 2.845 0 0 1 2.941-2.94 2.849 2.849 0 0 0 2.176-.901 2.847 2.847 0 0 1 4.159 0Z"/>
                  </svg>

                <span class="sr-only">Nomina Manicuristas</span>
            </button>
            <div id="tooltip-wallet" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Nomina Manicuristas
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <button data-tooltip-target="tooltip-settings" type="button"  wire:click="accion({{ 4 }})" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-7 h-7 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                  </svg>

                <span class="sr-only">Nomina Encargado</span>
            </button>
            <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Nomina Encargado
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <div class="flex items-center justify-center ">
                <button data-tooltip-target="tooltip-new" type="button" wire:click.prevent="accion({{ 3 }})" class="inline-flex items-center justify-center w-10 h-10 font-medium bg-[#7898a5] rounded-full hover:bg-[#5390a7] group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 shadow-[0_2.8px_2.2px_rgba(0,_0,_0,_0.034),_0_6.7px_5.3px_rgba(0,_0,_0,_0.048),_0_12.5px_10px_rgba(0,_0,_0,_0.06),_0_22.3px_17.9px_rgba(0,_0,_0,_0.072),_0_41.8px_33.4px_rgba(0,_0,_0,_0.086),_0_100px_80px_rgba(0,_0,_0,_0.12)]">
                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 16">
                        <path d="M12.5 3.046H10v-.928A2.12 2.12 0 0 0 8.8.164a1.828 1.828 0 0 0-1.985.311l-5.109 4.49a2.2 2.2 0 0 0 0 3.24L6.815 12.7a1.83 1.83 0 0 0 1.986.31A2.122 2.122 0 0 0 10 11.051v-.928h1a2.026 2.026 0 0 1 2 2.047V15a.999.999 0 0 0 1.276.961A6.593 6.593 0 0 0 12.5 3.046Z"/>
                    </svg>
                    <span class="sr-only">Atras</span>
                </button>
            </div>
            <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Atras
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <button data-tooltip-target="tooltip-empleados" type="button"  wire:click="accion({{ 5 }})"  class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-7 h-7 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                  </svg>

                <span class="sr-only">Empleados</span>
            </button>
            <div id="tooltip-empleados" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Empleados
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <button data-tooltip-target="tooltip-rm" type="button"  wire:click="accion({{ 6 }})" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-7 h-7 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm-6 9a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h.5a2.5 2.5 0 0 0 0-5H5Zm1.5 3H6v-1h.5a.5.5 0 0 1 0 1Zm4.5-3a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h1.376A2.626 2.626 0 0 0 15 15.375v-1.75A2.626 2.626 0 0 0 12.375 11H11Zm1 5v-3h.375a.626.626 0 0 1 .625.626v1.748a.625.625 0 0 1-.626.626H12Zm5-5a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1h1a1 1 0 1 0 0-2h-2Z" clip-rule="evenodd"/>
                  </svg>
                <span class="sr-only">Reporte Empleados</span>
            </button>
            <div id="tooltip-rm" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Reporte Empleados
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <button data-tooltip-target="tooltip-rg" type="button"  wire:click="accion({{ 7 }})" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-7 h-7 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
                  </svg>
                <span class="sr-only">Reporte General</span>
            </button>
            <div id="tooltip-rg" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Reporte General
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

        </div>
    </div>

</div>
