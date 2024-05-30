
<div>
    @livewire('notifications')
    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 mb-4 mt-4">
        {{-- Descripcion --}}
        @foreach ($data as $item)
        <div class="flex p-5">
            <div class="w-full" type="submit"  onclick="Livewire.dispatch('openModal', { component: 'detalle-asignacion', arguments: { disponible: {{ $item->id }} }})">
                {{-- Servicio activo y de color verde --}}
                @if($item->status == 'activo')
                <div id="{{ $item->id }}" class="flex justify-start rounded-full {{ $item->servicio_asignacion == 'vip' ? 'border border-[#be9e97] bg-[#ddccc9]' : 'bg-green-700' }}  px-6 py-3 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 w-full">
                        @if (Str::contains($item->area_trabajo, 'quiropedia'))
                        <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                        @endif
                        @if (Str::contains($item->area_trabajo, 'manicure'))
                        <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/mesas.png') }}" alt="">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-2xl font-extrabold text-white">
                                {{ $item->empleado }}
                            </p>
                            <p class="text-xs text-white font-bold">
                                Cliente: {{ $item->cliente }}
                            </p>
                            <p class="text-xs text-white font-bold">
                                Codigo: {{ $item->cod_asignacion }}
                            </p>
                            <p class="text-xs text-white font-bold">
                                Fecha: {{ $item->created_at }}
                            </p>
                            <p class="text-xs text-white font-bold">
                                Contador:
                                @livewire('count-down', ['start' => $item->id])
                            </p>

                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            @if($item->servicio_asignacion == 'vip')
                                <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/vip.png') }}" alt="">
                            @endif
                            @if($item->servicio_asignacion != 'vip')
                                <img class="w-14 h-auto rounded-full ml-4" src="{{ asset('images/open-lock.png') }}" alt="">
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Servicio cerrado y de color amarillo por facturar --}}
                @elseif($item->status == 'por facturar')
                <div class="flex justify-start rounded-full bg-yellow-400 px-6 py-3 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 w-full">
                        @if (Str::contains($item->area_trabajo, 'quiropedia'))
                        <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                        @endif
                        @if (Str::contains($item->area_trabajo, 'manicure'))
                        <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/mesas.png') }}" alt="">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-2xl font-extrabold text-white truncate dark:text-white">
                                {{ $item->empleado }}
                            </p>
                            <p class="text-xs text-white truncate dark:text-gray-400">
                                Cliente: {{ $item->cliente }}
                            </p>
                            <p class="text-xs text-white truncate dark:text-gray-400">
                                Codigo: {{ $item->cod_asignacion }}
                            </p>
                            <p class="text-xs text-white truncate dark:text-gray-400">
                                Fecha: {{ $item->created_at }}
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-bold text-white">
                            @if($item->servicio_asignacion == 'vip')
                                <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/vip.png') }}" alt="">
                            @endif
                            @if($item->servicio_asignacion != 'vip')
                            <img class="w-14 h-auto rounded-full ml-4" src="{{ asset('images/padlock.png') }}" alt="">
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @endforeach
        {{-- <div class="grid grid-flow-col gap-5 text-center auto-cols-max">
            <div class="flex flex-col p-2 bg-neutral rounded-box text-neutral-content">
              <span class="countdown font-mono text-5xl">
                <span style="--value:15;"></span>
              </span>
              days
            </div>
            <div class="flex flex-col p-2 bg-neutral rounded-box text-neutral-content">
              <span class="countdown font-mono text-5xl">
                <span style="--value:10;"></span>
              </span>
              hours
            </div>
            <div class="flex flex-col p-2 bg-neutral rounded-box text-neutral-content">
              <span class="countdown font-mono text-5xl">
                <span style="--value:24;"></span>
              </span>
              min
            </div>
            <div class="flex flex-col p-2 bg-neutral rounded-box text-neutral-content">
              <span class="countdown font-mono text-5xl">
                <span style="--value:51;"></span>
              </span>
              sec
            </div>
          </div> --}}

        {{-- div para separacion ene le diseno --}}
        <div class="w-full h-28"></div>

        {{-- Menu para table --}}
        <div class="fixed sm:z-0 md:z-0 lg:z-0 w-full h-16 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600">
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
                <button data-tooltip-target="tooltip-wallet" type="button" wire:click="#" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
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
                    <button data-tooltip-target="tooltip-new" type="button" wire:click.prevent="facturar_cliente" class="inline-flex items-center justify-center w-10 h-10 font-medium bg-[#7898a5] rounded-full hover:bg-[#5390a7] group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 shadow-[0_2.8px_2.2px_rgba(0,_0,_0,_0.034),_0_6.7px_5.3px_rgba(0,_0,_0,_0.048),_0_12.5px_10px_rgba(0,_0,_0,_0.06),_0_22.3px_17.9px_rgba(0,_0,_0,_0.072),_0_41.8px_33.4px_rgba(0,_0,_0,_0.086),_0_100px_80px_rgba(0,_0,_0,_0.12)]">
                        <svg class="w-4 h-4 mb-1 text-white dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                            <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10ZM17 13h-2v-2a1 1 0 0 0-2 0v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0-2Z"/>
                          </svg>
                        <span class="sr-only">Facturar cliente</span>
                    </button>
                </div>
                <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Facturar cliente
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <button data-tooltip-target="tooltip-settings" type="button"  wire:click="citas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M6 1a1 1 0 0 0-2 0h2ZM4 4a1 1 0 0 0 2 0H4Zm7-3a1 1 0 1 0-2 0h2ZM9 4a1 1 0 1 0 2 0H9Zm7-3a1 1 0 1 0-2 0h2Zm-2 3a1 1 0 1 0 2 0h-2ZM1 6a1 1 0 0 0 0 2V6Zm18 2a1 1 0 1 0 0-2v2ZM5 11v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 11v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 15v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 15v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 11v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM5 15v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM2 4h16V2H2v2Zm16 0h2a2 2 0 0 0-2-2v2Zm0 0v14h2V4h-2Zm0 14v2a2 2 0 0 0 2-2h-2Zm0 0H2v2h16v-2ZM2 18H0a2 2 0 0 0 2 2v-2Zm0 0V4H0v14h2ZM2 4V2a2 2 0 0 0-2 2h2Zm2-3v3h2V1H4Zm5 0v3h2V1H9Zm5 0v3h2V1h-2ZM1 8h18V6H1v2Zm3 3v.01h2V11H4Zm1 1.01h.01v-2H5v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H5v2h.01v-2ZM9 11v.01h2V11H9Zm1 1.01h.01v-2H10v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM9 15v.01h2V15H9Zm1 1.01h.01v-2H10v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM14 15v.01h2V15h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM14 11v.01h2V11h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM4 15v.01h2V15H4Zm1 1.01h.01v-2H5v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H5v2h.01v-2Z"/>
                    </svg>
                    <span class="sr-only">Citas</span>
                </button>
                <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Citas
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
</div>


