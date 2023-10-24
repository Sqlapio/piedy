@php
use Carbon\Carbon;
$h = Carbon::now('America/Caracas')->format('h:i:s');
@endphp

<div>
    <div class="p-2">
        <div class="flex align-items-center justify-center">
            <div class="ml-3 items-center">
                <div class="">Hora</div>
                <h1 wire:poll.1000ms class="font-bold text-3xl sm:text-4xl lg:text-5xl leading-9 text-primary ml-2">{{ $h }}</h1>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#ffcc99] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <img class="absolute bottom-0 left-0" src="https://png.pngtree.com/png-clipart/20220908/ourmid/pngtree-human-profile-avatar-3d-icon-render-png-image_6142330.png" alt="">

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-4 px-4 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card group 2 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#99ccff] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-4 px-4 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#ffcc99] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-end mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card group 2 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#99ccff] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#ffcc99] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-end mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card group 2 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#99ccff] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#ffcc99] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-end mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card group 2 -->
        <div class="p-2 flex justify-start max-w-md">
            <div class="flex rounded-lg h-full bg-[#99ccff] p-1 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center mb-1 p-2">
                    <div class="mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                    </div>
                    <h2 class="text-white dark:text-black text-sm font-extrabold">Gustavo Camacho</h2>
                </div>
                <div class="flex flex-col justify-center text-xs">
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Glory Fuentes</h2>
                    </div>
                    <div class="">
                        <h2 class="text-lg font-extrabold text-center text-[#ffb366]">Podologia basica</h2>
                    </div>
                </div>
                <div class="mt-6 flex justify-center items-center gap-8 text-xs">
                    <!-- Estado -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Duracion</p>
                            <p class="font-medium">120 Minutos</p>
                        </div>
                    </div>
                    <!-- Agencia -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Culminacion</p>
                            <p class="font-medium">12:30pm</p>
                        </div>
                    </div>
                    <!-- Foto -->
                    <div class="sm:inline-flex sm:shrink-0 sm:items-center sm:gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-check-blue">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <div class="mt-1.5 sm:mt-0">
                            <p class="text-gray-500">Costo</p>
                            <p class="font-medium">30$</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</div>

