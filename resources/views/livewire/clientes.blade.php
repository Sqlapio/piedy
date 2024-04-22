<div class="p-5">
    <div class="p-5 {{ $ocultar_form_cliente }}">
        @livewire('notifications')
        <h1 class="text-xl mb-6 font-bold text-[#bd9c95]">FICHA DEL CLIENTE</h1>
        {{-- tabla y boton del formulario de clientes --}}
        <div class="p-5 bg-[#e9d4cf] rounded-xl">
            <h1 class="text-xl mb-6 font-bold text-black">Datos Personales</h1>
            <div class="grid md:grid-cols-3 md:gap-6">
                <div class="relative z-0 w-full mb-6 group">
                    <x-input wire:model="nombre" right-icon="user" label="Nombre" placeholder="Nombre del cliente" />
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <x-input wire:model="apellido" right-icon="user" label="Apellido" placeholder="Apellido del cliente" />
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <x-input wire:model="cedula" right-icon="user" label="Cédula de Identidad" placeholder="Ejemplo: 16543678" />
                </div>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6 mt-5">
                <div class="relative z-0 w-full mb-6 group">
                    <x-input wire:model="email" right-icon="user" label="Email" placeholder="Email del cliente" />
                    {{-- <label for="floating_phone" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label> --}}
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <x-inputs.maskable wire:model="telefono" label="Número Telefónico" mask="#### #######" placeholder="Número telefónico" />
                    {{-- <label for="floating_company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Telefono</label> --}}
                </div>
            </div>
        </div>

        {{-- Encuenta --}}
        <div class="p-5 mt-5 bg-[#e9d4cf] rounded-xl">
            <h1 class="text-xl mb-6 font-bold text-black">Ficha Médica</h1>
            <h1 class="text-sm mb-3 font-bold text-black">Antecedentes Médicos:</h1>
            <div class="grid md:grid-cols-1 md:gap-2 mb-7">
                {{-- Pregunta 1 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">1.- ¿Tiene alguna enfermedad crónica? (ej. diabetes, enfermedades cardiovasculares, etc.)</p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="am_p1">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
                {{-- pregunta 2 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">2.- ¿Toma algún medicamento de forma regular? </p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="am_p2">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
                {{-- pregunta 3 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">3.- ¿Ha tenido alguna cirugía reciente? </p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="am_p3">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
                {{-- pregunta 4 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">4.- ¿Padece de alguna alergia (medicamentos, materiales, etc.)?</p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="am_p4">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
            </div>

            <h1 class="text-sm mb-3 font-bold text-black">Historial Podológico:</h1>
            <div class="grid md:grid-cols-1 md:gap-2 mb-7">
                {{-- Pregunta 1 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">1.- ¿Ha tenido problemas podológicos anteriores? (ej. hongos en las uñas, callos, juanetes, etc.)</p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model.live="hp_p1">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>
                    </div>
                </div>
                {{-- pregunta 2 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">2.- ¿Ha tenido alguna lesión en los pies recientemente? </p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model.live="hp_p2">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>
                    </div>
                </div>
            </div>

            <h1 class="text-sm mb-3 font-bold text-black">Estilo de Vida:</h1>
            <div class="grid md:grid-cols-1 md:gap-2 mb-7">
                {{-- Pregunta 1 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">1.- ¿Realiza algún tipo de actividad física regularmente?</p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="ev_p1">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
                {{-- pregunta 2 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">2.- ¿Pasa mucho tiempo de pie durante el día? </p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="ev_p2">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
                {{-- pregunta 3 --}}
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">3.- ¿Usa calzado adecuado para la actividad que realiza?</p>
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" wire:model="ev_p3">
                            <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                            <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                            <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                        </label>

                    </div>
                </div>
            </div>

            <h1 class="text-sm mb-1 font-bold text-black">Comentarios Adicionales:</h1>
            <textarea wire:model="comentario_adicional" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="¿Hay algún otro detalle médico o preocupación que le gustaría compartir con nosotros?"></textarea>
        </div>

        {{-- Boton --}}
        <div class="flex justify-end py-5 mt-auto">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-3 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Guardar cliente</span>
            </button>
        </div>
    </div>

    <div class="p-5">
        {{-- Table de clientes registrados --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg  {{ $ocultar_table_cliente }}">
            <div class="flex justify-start mb-4">
                <input wire:model.live="buscar" type="search" id="search" name="buscar" class="border-b border-gray-200 py-2 text-sm rounded-full shadow-lg focus:ring-check-blue focus:border-check-blue" placeholder="Buscar cliente" autocomplete="off">
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-[#7898a5] dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cédula de Identidad
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Teléfono
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Registrado por:
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ficha Médica
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Asginar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item->nombre.' '.$item->apellido }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $item->cedula }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->email }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->telefono }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->responsable }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <x-avatar sm label="FM" class="bg-green-600"/>
                        </th>
                        <td class="px-6 py-4 text-center items-center">
                            {{-- <svg onclick="Livewire.dispatch('openModal', { component: 'modal-clientes', arguments: { cliente: {{ $item->id }} }})" class="w-6 h-6 text-green-700 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 17">
                                <path d="M2.057 6.9a8.718 8.718 0 0 1 6.41-3.62v-1.2A2.064 2.064 0 0 1 9.626.2a1.979 1.979 0 0 1 2.1.23l5.481 4.308a2.107 2.107 0 0 1 0 3.3l-5.479 4.308a1.977 1.977 0 0 1-2.1.228 2.063 2.063 0 0 1-1.158-1.876v-.942c-5.32 1.284-6.2 5.25-6.238 5.44a1 1 0 0 1-.921.807h-.06a1 1 0 0 1-.953-.7A10.24 10.24 0 0 1 2.057 6.9Z"/>
                              </svg> --}}
                              {{-- <svg onclick="Livewire.dispatch('openModal', { component: 'modal-clientes', arguments: { cliente: {{ $item->id }} }})" class="w-7 h-7 text-green-700 dark:text-white cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 17">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 16 4-4-4-4m6 8 4-4-4-4"/>
                              </svg> --}}
                              {{-- <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-gray-700 dark:text-gray-400 border border-gray-500 ">
                                <svg onclick="Livewire.dispatch('openModal', { component: 'modal-clientes', arguments: { cliente: {{ $item->id }} }})" class="w-2.5 h-2.5 me-1.5 text-green-700 dark:text-white cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 17">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 16 4-4-4-4m6 8 4-4-4-4"/>
                                  </svg>
                                Asignar servício
                                </span> --}}
                                <span onclick="Livewire.dispatch('openModal', { component: 'modal-clientes', arguments: { cliente: {{ $item->id }} }})" class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-2.5 rounded-xl dark:bg-gray-700 dark:text-green-400 border border-green-400 cursor-pointer">Asignar Servicio</span>

                              {{-- onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginacion --}}
        <div class="bg-white px-4 py-3 mt-4 items-center justify-between border-t border-gray-200 sm:px-6 {{ $ocultar_table_cliente }}">
            {{-- Paginacion --}}
            {{ $data->links() }}
        </div>
    </div>

    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    {{-- Menu para table --}}
    <div class="fixed sm:z-0 md:z-0 lg:z-50 w-full h-16 mt-5 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600">
        <div class="grid h-full max-w-lg grid-cols-7 mx-auto ">
            <button data-tooltip-target="tooltip-home" type="button" wire:click="inicio" class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                <span class="sr-only">Inicio</span>
            </button>
            <div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Inicio
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-wallet" type="button" wire:click="productos" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                    <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                </svg>
                <span class="sr-only">Productos</span>
            </button>
            <div id="tooltip-wallet" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Productos
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-products" type="button" wire:click="cabinas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 0H1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1Zm14 0h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1ZM5 14H1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1Zm14 0h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1ZM12 2H8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2Zm0 14H8a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2Zm-8-4V8a1 1 0 0 0-2 0v4a1 1 0 1 0 2 0Zm14 0V8a1 1 0 0 0-2 0v4a1 1 0 0 0 2 0Z"/>
                </svg>
                <span class="sr-only">Cabinas</span>
            </button>
            <div id="tooltip-products" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Cabinas
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <div class="flex items-center justify-center ">
                <button data-tooltip-target="tooltip-new" type="button" wire:click.prevent="ocultar_table()" class="inline-flex items-center justify-center w-10 h-10 font-medium bg-[#7898a5] rounded-full hover:bg-[#5390a7] group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 shadow-[0_2.8px_2.2px_rgba(0,_0,_0,_0.034),_0_6.7px_5.3px_rgba(0,_0,_0,_0.048),_0_12.5px_10px_rgba(0,_0,_0,_0.06),_0_22.3px_17.9px_rgba(0,_0,_0,_0.072),_0_41.8px_33.4px_rgba(0,_0,_0,_0.086),_0_100px_80px_rgba(0,_0,_0,_0.12)]">
                    <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                    </svg>
                    <span class="sr-only">Crear cliente</span>
                </button>
            </div>
            <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Crear cliente
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <button data-tooltip-target="tooltip-settings" type="button"  wire:click="citas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path fill="currentColor" d="M6 1a1 1 0 0 0-2 0h2ZM4 4a1 1 0 0 0 2 0H4Zm7-3a1 1 0 1 0-2 0h2ZM9 4a1 1 0 1 0 2 0H9Zm7-3a1 1 0 1 0-2 0h2Zm-2 3a1 1 0 1 0 2 0h-2ZM1 6a1 1 0 0 0 0 2V6Zm18 2a1 1 0 1 0 0-2v2ZM5 11v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 11v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 15v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 15v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 11v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM5 15v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM2 4h16V2H2v2Zm16 0h2a2 2 0 0 0-2-2v2Zm0 0v14h2V4h-2Zm0 14v2a2 2 0 0 0 2-2h-2Zm0 0H2v2h16v-2ZM2 18H0a2 2 0 0 0 2 2v-2Zm0 0V4H0v14h2ZM2 4V2a2 2 0 0 0-2 2h2Zm2-3v3h2V1H4Zm5 0v3h2V1H9Zm5 0v3h2V1h-2ZM1 8h18V6H1v2Zm3 3v.01h2V11H4Zm1 1.01h.01v-2H5v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H5v2h.01v-2ZM9 11v.01h2V11H9Zm1 1.01h.01v-2H10v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM9 15v.01h2V15H9Zm1 1.01h.01v-2H10v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM14 15v.01h2V15h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM14 11v.01h2V11h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM4 15v.01h2V15H4Zm1 1.01h.01v-2H5v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H5v2h.01v-2Z"/>
                </svg>
                <span class="sr-only">Agenda</span>
            </button>
            <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Agenda
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-services" type="button"  wire:click="servicios" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M17 11h-2.722L8 17.278a5.512 5.512 0 0 1-.9.722H17a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM6 0H1a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V1a1 1 0 0 0-1-1ZM3.5 15.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM16.132 4.9 12.6 1.368a1 1 0 0 0-1.414 0L9 3.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z"/>
                </svg>
                <span class="sr-only">Servicios</span>
            </button>
            <div id="tooltip-services" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Servicios
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="tooltip-profile" type="button"  wire:click="clientes"  class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                </svg>
                <span class="sr-only">Clientes</span>
            </button>
            <div id="tooltip-profile" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Clientes
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</div>
