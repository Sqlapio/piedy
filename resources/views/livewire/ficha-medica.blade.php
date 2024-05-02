<div>
    <div class="p-5 mt-5 bg-[#e9d4cf] rounded-xl">
        <h1 class="text-xl mb-6 font-bold text-black">Antecedentes Médicos:</h1>
        <div class="grid md:grid-cols-1 md:gap-6">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">1.- ¿Tiene alguna enfermedad crónica? (ej. diabetes, enfermedades cardiovasculares, etc.)</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
                    </label>

                </div>
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
        <div class="flex justify-end p-2 mt-auto">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Guardar cliente</span>
            </button>
        </div>
    </div>

    <div class="p-5 mt-5 bg-[#e9d4cf] rounded-xl">
        <h1 class="text-xl mb-6 font-bold text-black">Antecedentes Médicos:</h1>
        <div class="grid md:grid-cols-1 md:gap-6">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">1.- ¿Tiene alguna enfermedad crónica? (ej. diabetes, enfermedades cardiovasculares, etc.)</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
                    </label>

                </div>
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
        <div class="flex justify-end p-2 mt-auto">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Guardar cliente</span>
            </button>
        </div>
    </div>

    <div class="p-5 mt-5 bg-[#e9d4cf] rounded-xl">
        <h1 class="text-xl mb-6 font-bold text-black">Antecedentes Médicos:</h1>
        <div class="grid md:grid-cols-1 md:gap-6">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">1.- ¿Tiene alguna enfermedad crónica? (ej. diabetes, enfermedades cardiovasculares, etc.)</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
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
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">SI</span>
                    </label>

                </div>
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
        <div class="flex justify-end p-2 mt-auto">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Guardar cliente</span>
            </button>
        </div>
    </div>
</div>
