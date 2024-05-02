<div class="p-4">
    {{-- Encuenta --}}
    <div class="p-3 mt-2 bg-[#e9d4cf] rounded-xl">
        <h1 class="text-xl mb-6 font-bold text-black">Ficha Médica</h1>
        <div class="grid md:grid-cols-1 md:gap-2 mb-3">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-medium">1.- ¿Tiene alguna condición médica preexistente o alergias que debamos conocer? <br>(Por ejemplo, diabetes, problemas circulatorios, alergias a productos químicos o cosméticos)</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" wire:model="p1">
                        <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-1 md:gap-2 mb-3">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-medium">2.- ¿Sufre de alguna de las siguientes afecciones en los pies? <br>(Por ejemplo, hongos en las uñas, callosidades, juanetes, pie diabético)</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" wire:model.live="p2">
                        <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-1 md:gap-2 mb-3">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-medium">3.- ¿Ha experimentado alguna reacción adversa a productos utilizados en tratamientos de manicure o pedicure anteriores? <br>Si es así, ¿podría detallar qué productos o sustancias causaron la reacción?</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" wire:model="p3">
                        <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                    </label>

                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-1 gap-7 mb-3">
            {{-- Pregunta 1 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-medium">4.- ¿Tiene alguna preferencia específica o necesita algún ajuste especial durante su tratamiento de quiropedia o manicure?<br>(Por ejemplo, evitar el uso de ciertos esmaltes o productos)</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" wire:model="p4">
                        <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                    </label>

                </div>
            </div>
            
        </div>

        <div class="grid md:grid-cols-1 gap-7 mb-3">
            {{-- Pregunta 5 --}}
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-medium">5.- ¿Consiente en recibir tratamientos de quiropedia y manicure según las prácticas estándar de nuestro establecimiento?</p>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" wire:model="p5">
                        <span class="mr-1 text-xs font-medium text-red-800 dark:text-gray-300">No</span>
                        <div class="relative w-11 h-6 bg-red-700 peer-focus:outline-none dark:peer-focus:ring-green-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-5 after:h-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-700"></div>
                        <span class="ms-1 text-xs font-medium text-green-800 dark:text-gray-300">Si</span>
                    </label>
    
                </div>
            </div>
        </div>

        <h1 class="text-xs font-bold text-black">Comentarios Adicionales:</h1>
        <textarea wire:model="comentario_adicional" id="message" rows="2" class="block p-2.5 mt-1 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="¿Hay algún otro detalle médico o preocupación que le gustaría compartir con nosotros?"></textarea>
        <footer class="flex justify-center w-full">
            <div class="p-3">
                <p class="text-xs text-center">
                    Estas preguntas ayudan a recopilar información crucial para proporcionar un servicio seguro y personalizado, tomando en cuenta las necesidades médicas y las preferencias de los clientes.
                </p>
            </div>
        </footer>
        <button
            type="button"
            wire:click="store()"
            class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span>Actualizar Datos</span>
        </button>
    </div>
</div>
