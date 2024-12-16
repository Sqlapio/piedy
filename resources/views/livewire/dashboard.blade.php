@php
use App\Models\TasaBcv as ModelsTasaBcv;
    $tasa = ModelsTasaBcv::first();
    if(Auth::user()->tipo_usuario == 'gerente')
    {
        $style = 'lg:grid-cols-3';
    }else{
        $style = 'lg:grid-cols-2';
    }
@endphp
<div class="py-5 my-auto sm:px-5 md:px-10 lg:px-20">

    @if(Auth::user()->rol_id == 3 || Auth::user()->rol_id == 4)
        <div class="grid grid-cols-2 gap-4 p-3">
            {{-- TASA BCV --}}
            <div class="cursor-pointer flex items-center border p-1 rounded-xl shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]"
                onclick="Livewire.dispatch('openModal', { component: 'tasa-bcv' })"
                style="background-image: url('https://media.istockphoto.com/id/624878906/es/foto/fondo-abstracto-triangular.jpg?b=1&s=170667a&w=0&k=20&c=UTL2PU75t1yyJB_C9ORFOsA7LgkvxIZncxK7A44gGGA='); background-size: cover;">
                <img class="w-14 h-14 m-2 rounded-full" src="{{ asset('images/BCV.png') }}" alt="">
                <div class="ml-2 titulos">
                    <div class="font-bold dark:text-white">BCV: {{ $tasa->tasa }}Bs.</div>
                    {{-- <div class="text-sm text-gray-500 dark:text-gray-400">Tasa del dia: {{ $tasa->tasa }}</div> --}}
                </div>
            </div>
            <div wire:click="gift()"
                class="cursor-pointer flex items-center border p-4 rounded-xl shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]"
                style="background-image: url('https://media.istockphoto.com/id/624878906/es/foto/fondo-abstracto-triangular.jpg?b=1&s=170667a&w=0&k=20&c=UTL2PU75t1yyJB_C9ORFOsA7LgkvxIZncxK7A44gGGA='); background-size: cover;">
                    <div class="ml-1 titulos">
                        <div class="text-sm text-black font-bold dark:text-gray-400">GIFTCARD/MEMBRESIA</div>
                    </div>
            </div>

        </div>

        {{-- Primera linea --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-2 mb-2 px-3">
            {{-- Clientes --}}
            <div wire:click="valida_tasa({{ 2 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/13.png'); background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                        <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                      </svg>
                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black leading-7 font-bold">
                        CLIENTES
                    </div>
                    <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                        <div>Registro y gestion de clientes</div>
                    </div>
                </div>
            </div>
            {{-- Cabinas --}}
            <div wire:click="valida_tasa({{ 3 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/15.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                      </svg>
                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black leading-7 font-bold">
                        ÁREA TRABAJO
                    </div>
                        <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                            <div>Estaciones de Trabajo</div>
                        </div>
                </div>
            </div>
            {{-- Agenda --}}
            <div wire:click="valida_tasa({{ 4 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/18.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" />
                        <path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
                      </svg>
                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black leading-7 font-bold">
                        AGENDA
                    </div>
                        <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                            <div>Agenda tus clientes</div>
                        </div>
                </div>
            </div>
        </div>

        {{-- Segunda linea --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-2 mb-2 px-3">
            {{-- MANEJO DE INVENTARIO --}}
            <a href="{{ route('recepcion-inventario') }}" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/17.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z" />
                        <path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z" />
                        <path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                      </svg>
                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black text- leading-7 font-bold">
                        RECEPCION DE INVENTARIO
                    </div>
                    <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                        <div>Gestion de inventario</div>
                    </div>
                </div>
            </a>
            {{-- MANEJO DE INVENTARIO --}}
            <a href="{{ route('inventario') }}" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/4.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path d="M11.644 1.59a.75.75 0 0 1 .712 0l9.75 5.25a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.712 0l-9.75-5.25a.75.75 0 0 1 0-1.32l9.75-5.25Z" />
                        <path d="m3.265 10.602 7.668 4.129a2.25 2.25 0 0 0 2.134 0l7.668-4.13 1.37.739a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.71 0l-9.75-5.25a.75.75 0 0 1 0-1.32l1.37-.738Z" />
                        <path d="m10.933 19.231-7.668-4.13-1.37.739a.75.75 0 0 0 0 1.32l9.75 5.25c.221.12.489.12.71 0l9.75-5.25a.75.75 0 0 0 0-1.32l-1.37-.738-7.668 4.13a2.25 2.25 0 0 1-2.134-.001Z" />
                      </svg>                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black text- leading-7 font-bold">
                        INVENTARIO GENERAL
                    </div>
                    <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                        <div>Gestion de inventario</div>
                    </div>
                </div>
            </a>
            {{-- Productos --}}
            <a wire:click="valida_tasa({{ 5 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/3.png'); background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                      </svg>
                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black leading-7 font-bold">
                        PRODUCTOS
                    </div>
                    <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                        <div>Carga y gestion de productos</div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Segunda linea --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-2 mb-2 px-3">
            {{-- Asigancion de Material --}}
            <a href="{{ route('material') }}" class="p-6 rounded-lg" style="background-image: url('images/10.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path d="M11.25 5.337c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.036 1.007-1.875 2.25-1.875S15 2.34 15 3.375c0 .369-.128.713-.349 1.003-.215.283-.401.604-.401.959 0 .332.278.598.61.578 1.91-.114 3.79-.342 5.632-.676a.75.75 0 0 1 .878.645 49.17 49.17 0 0 1 .376 5.452.657.657 0 0 1-.66.664c-.354 0-.675-.186-.958-.401a1.647 1.647 0 0 0-1.003-.349c-1.035 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401.31 0 .557.262.534.571a48.774 48.774 0 0 1-.595 4.845.75.75 0 0 1-.61.61c-1.82.317-3.673.533-5.555.642a.58.58 0 0 1-.611-.581c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.035-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959a.641.641 0 0 1-.658.643 49.118 49.118 0 0 1-4.708-.36.75.75 0 0 1-.645-.878c.293-1.614.504-3.257.629-4.924A.53.53 0 0 0 5.337 15c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.036 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.369 0 .713.128 1.003.349.283.215.604.401.959.401a.656.656 0 0 0 .659-.663 47.703 47.703 0 0 0-.31-4.82.75.75 0 0 1 .83-.832c1.343.155 2.703.254 4.077.294a.64.64 0 0 0 .657-.642Z" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-xl text-black text- leading-7 font-bold">
                        MATERIAL
                    </div>
                        <div class="text-right text-sm font-semibold text-black">
                            <div>Control de materiales asignados para uso diario</div>
                        </div>
                </div>
            </a>
            {{-- Cierre diario --}}
            <div wire:click="valida_tasa({{ 9 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/12.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 0 0 3 3h.27l-.155 1.705A1.875 1.875 0 0 0 7.232 22.5h9.536a1.875 1.875 0 0 0 1.867-2.045l-.155-1.705h.27a3 3 0 0 0 3-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0 0 18 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM16.5 6.205v-2.83A.375.375 0 0 0 16.125 3h-8.25a.375.375 0 0 0-.375.375v2.83a49.353 49.353 0 0 1 9 0Zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 0 1-.374.409H7.232a.375.375 0 0 1-.374-.409l.526-5.784a.373.373 0 0 1 .333-.337 41.741 41.741 0 0 1 8.566 0Zm.967-3.97a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H18a.75.75 0 0 1-.75-.75V10.5ZM15 9.75a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V10.5a.75.75 0 0 0-.75-.75H15Z" clip-rule="evenodd" />
                      </svg>
                      
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black text- leading-7 font-bold">
                        CIERRE DIARIO
                    </div>
                        <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                            <div>Cierre de caja</div>
                        </div>
                </div>
            </div>

            {{-- Ventas --}}
            <div wire:click="valida_tasa({{ 9 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('images/11.png');background-size: 100% 100%;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-16">
                        <path fill-rule="evenodd" d="M2.25 2.25a.75.75 0 0 0 0 1.5H3v10.5a3 3 0 0 0 3 3h1.21l-1.172 3.513a.75.75 0 0 0 1.424.474l.329-.987h8.418l.33.987a.75.75 0 0 0 1.422-.474l-1.17-3.513H18a3 3 0 0 0 3-3V3.75h.75a.75.75 0 0 0 0-1.5H2.25Zm6.04 16.5.5-1.5h6.42l.5 1.5H8.29Zm7.46-12a.75.75 0 0 0-1.5 0v6a.75.75 0 0 0 1.5 0v-6Zm-3 2.25a.75.75 0 0 0-1.5 0v3.75a.75.75 0 0 0 1.5 0V9Zm-3 2.25a.75.75 0 0 0-1.5 0v1.5a.75.75 0 0 0 1.5 0v-1.5Z" clip-rule="evenodd" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black leading-7 font-bold">
                        VENTAS
                    </div>
                        <div class="sm:hidden md:hidden lg:block text-right text-sm font-semibold text-black">
                            <div>Modulos de ventas</div>
                        </div>
                </div>
            </div>

        </div>



    @endif

    @if(Auth::user()->rol_id == 6)
        {{-- tercera linea --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 px-3">
            {{-- Empleados --}}
            <div wire:click="valida_tasa({{ 1 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/fondo-abstracto-poligonal-azul_706163-3266.jpg'); background-size: cover;">
                <div class="flex items-center w-24 h-24"></div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-4xl text-white leading-7 font-bold">
                        Empleados
                    </div>
                    <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-white">
                        <div>Registro y gestion de Empleados</div>
                    </div>
                </div>
            </div>
            {{-- Nomina --}}
            <div wire:click="valida_tasa({{ 10 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/fondo-abstracto-poligonal-azul_706163-3266.jpg');background-size: cover;">
                <div class="flex items-center w-24 h-24"></div>
                <div class="ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-4xl text-white text- leading-7 font-bold">
                        Nomina
                    </div>
                    <div class="mt-3 text-right text-xs font-semibold text-white">
                        <div>Modulo para el calculo de nomina</div>
                    </div>
                </div>
            </div>
            {{-- Reportes --}}
            <div wire:click="valida_tasa({{ 11 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/fondo-abstracto-poligonal-azul_706163-3266.jpg');background-size: cover;">
                <div class="flex items-center w-24 h-24"></div>
                <div class="ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-4xl text-white text- leading-7 font-bold">
                        Reportes
                    </div>
                    <div class="mt-3 text-right text-xs font-semibold text-white">
                        <div>Modulo para gestion de reportes</div>
                    </div>
                </div>
            </div>
            {{-- Reportes General --}}
            <div wire:click="valida_tasa({{ 12 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/fondo-abstracto-poligonal-azul_706163-3266.jpg');background-size: cover;">
                <div class="flex items-center w-24 h-24"></div>
                <div class="ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-4xl text-white text- leading-7 font-bold">
                        Reporte General
                    </div>
                    <div class="mt-3 text-right text-xs font-semibold text-white">
                        <div>Modulo para gestion de reportes</div>
                    </div>
                </div>
            </div>
            {{-- Cierre Financiero --}}
            <div wire:click="valida_tasa({{ 14 }})" class="cursor-pointer p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/fondo-abstracto-poligonal-azul_706163-3266.jpg');background-size: cover;">
                <div class="flex items-center w-24 h-24"></div>
                <div class="ml-12 text-right">
                    <div class="sm:hidden md:hidden lg:block mt-2 text-4xl text-white text- leading-7 font-bold">
                        Cierre Financiero
                    </div>
                    <div class="mt-3 text-right text-xs font-semibold text-white">
                        <div>Modulo para Cierre Financiero</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Menu para los empleados -->
    @if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2)
        {{-- Cabinas --}}
        <div class="px-3 max-w-96">
            <div wire:click="valida_tasa({{ 3 }})" class="max-w-96 cursor-pointer p-6 rounded-lg" style="background-image: url('https://img.freepik.com/fotos-premium/primer-plano-fondo-abstracto-colorido-triangulos-ai-generativo_561855-19933.jpg');background-size: cover;">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                      </svg>
                </div>
                <div class="ml-12 text-right">
                    <div class="mt-2 text-xl text-black leading-7 font-bold">
                        ÁREA TRABAJO
                    </div>
                        <div class="sm:hidden md:hidden lg:block mt-3 text-right text-xs font-semibold text-black">
                            <div>Estaciones de Trabajo</div>
                        </div>
                </div>
            </div>
        </div>
    @endif

</div>


