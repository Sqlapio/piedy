@php
use App\Models\TasaBcv as ModelsTasaBcv;
    $tasa = ModelsTasaBcv::first();
@endphp
<div class="py-1 my-auto">

    @if(Auth::user()->tipo_usuario == 'gerente')
        {{-- BCV linea --}}
        <div class="grid grid-cols-1 gap-4 p-3">
            {{-- TASA BCV --}}
            <div class="flex items-center space-x-4 p-2" onclick="Livewire.dispatch('openModal', { component: 'tasa-bcv' })">
                <img class="w-14 h-14 rounded-full" src="{{ asset('images/BCV.png') }}" alt="">
                <div class="titulos">
                    <div class="font-bold dark:text-white">BCV</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Tasa del dia: {{ $tasa->tasa }}</div>
                </div>
            </div>
        </div>

        {{-- Primera linea --}}
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-2 mb-2 px-3">
            {{-- Empleados --}}
            <div wire:click="valida_tasa({{ 1 }})" class="p-6 rounded-lg" style="background-image: url('https://img.favpng.com/0/11/4/polygon-geometry-plane-desktop-wallpaper-png-favpng-e7CGay7DssGUF8FwFkeWviuCM.jpg'); background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        Empleados
                    </div>
                    <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                        <div>Registro y gestion de Empleados</div>
                    </div>
                </div>
            </div>
            {{-- Clientes --}}
            <div wire:click="valida_tasa({{ 2 }})" class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-geometry-pattern-hd-wallpaper_1000823-2187.jpg'); background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        CLIENTES
                    </div>
                    <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                        <div>Registro y gestion de clientes</div>
                    </div>
                </div>
            </div>
            {{-- Cabinas --}}
            <div wire:click="valida_tasa({{ 3 }})" class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/primer-plano-fondo-abstracto-colorido-triangulos-ai-generativo_561855-19933.jpg');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        ÁREA TRABAJO
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Estaciones de Trabajo</div>
                        </div>
                </div>
            </div>
        </div>

        {{-- Segunda linea --}}
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-2 mb-2 px-3">
            {{-- Agenda --}}
            <div wire:click="valida_tasa({{ 4 }})" class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-hd-wallpaper_1000823-2469.jpg?size=626&ext=jpg&ga=GA1.1.1016474677.1696809600&semt=ais');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        AGENDA
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Agenda tus clientes</div>
                        </div>
                </div>
            </div>
            {{-- Productos --}}
            <div wire:click="valida_tasa({{ 5 }})" class="p-6 rounded-lg" style="background-image: url('https://cdn.pixabay.com/photo/2017/03/25/18/06/color-2174066_640.png'); background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        PRODUCTOS
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Carga y gestion de productos</div>
                        </div>
                </div>
            </div>
            {{-- Servicios --}}
            <div wire:click="valida_tasa({{ 6 }})" class="p-6 rounded-lg" style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20201015/pngtree-modern-low-poly-background-with-red-and-blue-gradient-colors-image_417695.jpg');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z" />
                    </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black text- leading-7 font-bold">
                        SERVICIOS
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Carga y gestion de servicios</div>
                        </div>
                </div>
            </div>
        </div>

        {{-- tercera linea --}}
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-2 px-3">

            {{-- Ventas --}}
            <div wire:click="valida_tasa({{ 7 }})" class="p-6 rounded-lg" style="background-image: url('https://static.vecteezy.com/system/resources/previews/000/406/488/original/background-wallpaper-with-polygons-in-gradient-colors-vector.jpg');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black leading-7 font-bold">
                        VENTAS
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Modulos de ventas</div>
                        </div>
                </div>
            </div>
            {{-- gastos --}}
            <div wire:click="valida_tasa({{ 8 }})" class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/papel-tapiz-triangulo-abstracto-colores-pastel-claros-coloridos-banner-panorama-generativo-ai_699690-19035.jpg');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15h2.25m8.024-9.75c.011.05.028.1.052.148.591 1.2.924 2.55.924 3.977a8.96 8.96 0 01-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398C20.613 14.547 19.833 15 19 15h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 00.303-.54m.023-8.25H16.48a4.5 4.5 0 01-1.423-.23l-3.114-1.04a4.5 4.5 0 00-1.423-.23H6.504c-.618 0-1.217.247-1.605.729A11.95 11.95 0 002.25 12c0 .434.023.863.068 1.285C2.427 14.306 3.346 15 4.372 15h3.126c.618 0 .991.724.725 1.282A7.471 7.471 0 007.5 19.5a2.25 2.25 0 002.25 2.25.75.75 0 00.75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 002.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-2xl text-black text- leading-7 font-bold">
                        GASTOS
                    </div>
                        <div class="mt-3 text-right text-xs font-semibold text-black">
                            <div>Reporta tus gastos</div>
                        </div>
                </div>
            </div>
            {{-- Cierre diario --}}
            <div wire:click="valida_tasa({{ 9 }})" class="p-6 rounded-lg" style="background-image: url('https://elucubracion.com/wp-content/uploads/2014/05/fondo-abstracto-1.png');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-2xl text-black text- leading-7 font-bold">
                        CIERRE DIARIO
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Cierre de caja</div>
                        </div>
                </div>
            </div>
        </div>

    @endif

    {{-- Primera linea para empleados --}}
    @if(Auth::user()->tipo_usuario == 'empleado')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                {{-- Perfil --}}
                <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-geometry-pattern-hd-wallpaper_1000823-2187.jpg'); background-size: cover;">
                    <a href="{{ route('perfil') }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                    </div>
                    <div class="ml-12 text-right">
                        <div class="mt-2 text-2xl text-black leading-7 font-bold">
                            MI PERFIL
                        </div>
                            <div class="mt-3 text-right text-md font-semibold text-black">
                                <div>Informacion de usuario</div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- Servicios Asignado --}}
                {{-- <div class="p-6 rounded-lg" style="background-image: url('/images/empleados.jpg'); background-size: cover;">
                    <a href="{{ route('servicio_asignado') }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                        </svg>
                    </div>
                    <div class="ml-12 text-right">
                        <div class="mt-2 text-2xl text-black leading-7 font-bold">
                            SERVICIO ASIGNADOS
                        </div>
                            <div class="mt-3 text-right text-md font-semibold text-black">
                                <div>Servício asignado</div>
                            </div>
                        </a>
                    </div>
                </div> --}}
                {{-- Historico --}}
                <div class="p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/abstract-light-blue-background-hd-wallpaper_1000823-2469.jpg?size=626&ext=jpg&ga=GA1.1.1016474677.1696809600&semt=ais');background-size: cover;">
                    <a href="{{ route('historico_servicios') }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <div class="ml-12 text-right">
                        <div class="mt-2 text-2xl text-black leading-7 font-bold">
                            HITORICO DE SERVICIOS
                        </div>
                            <div class="mt-3 text-right text-md font-semibold text-black">
                                <div>Lista de servicio realizados</div>
                            </div>
                        </a>
                    </div>
                </div>
        </div>
    @endif

</div>


