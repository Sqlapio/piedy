@php
use Carbon\Carbon;
$h = Carbon::now('America/Caracas')->format('h:i:s');
@endphp

<div>
    {{-- <div class="p-2">
        <div class="flex align-items-center justify-center">
            <div class="ml-3 items-center">
                <div class="">Hora</div>
                <h1 wire:poll.1000ms class="font-bold text-3xl sm:text-4xl lg:text-5xl leading-9 text-primary ml-2">{{ $h }}</h1>
            </div>
        </div>
    </div> --}}
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 mb-4 mt-4">
        {{-- Descripcion --}}
        @foreach ($data as $item)
        <div class="flex justify-center p-2">
            <div class="max-w-md">
                <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                        <div class="flex items-center space-x-4 rounded-full">
                            @if (Str::contains($item->area_trabajo, 'c'))
                            <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                            @endif
                            @if (Str::contains($item->area_trabajo, 'm'))
                            <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/mesas.png') }}" alt="">
                            @endif
                            <div class="font-medium dark:text-white">
                                <div>{{ $item->empleado }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: {{ $item->cliente }}</div>
                            </div>
                            <div class="font-medium dark:text-white">
                                <div class="ml-10 mr-4">
                                    <x-dropdown>
                                        <x-dropdown.header label="Servicio">
                                            <x-dropdown.item icon="cog" label="Detalle" type="submit"  onclick="Livewire.dispatch('openModal', { component: 'detalle-asignacion', arguments: { disponible: {{ $item->id }} }})"/>
                                        </x-dropdown.header>
                                    </x-dropdown>
                                </div> 
                            </div>
                        </div>
                </div>
            </div>
        </div>
        @endforeach
        {{-- <div class="p-2">
            <div class="flex justify-star max-w-md">
                <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                        <div class="flex items-center space-x-4 rounded-full">
                            <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/mesas.png') }}" alt="">
                            <div class="font-medium dark:text-white">
                                <div>Gustavo Camacho</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                            </div>
                            <div class="font-medium dark:text-white">
                                <div class="ml-10 mr-4">
                                    <x-dropdown>
                                        <x-dropdown.header label="Servicio">
                                            <x-dropdown.item icon="cog" label="Detalle" />
                                        </x-dropdown.header>
                                    </x-dropdown>
                                </div> 
                            </div>
                        </div>
                </div>
            </div>
        </div> --}}
    </div>

    {{-- <div class="flex justify-center">
        <!-- card group 1 -->
        
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-16 h-16 rounded-full ml-4" src="https://killapunchay.com/wp-content/uploads/2019/11/Productos-Noviembre-Sin-Dise%C3%B1o28.jpg" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-16 h-16 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-16 h-16 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div> --}}
    
    {{-- <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="flex justify-start max-w-md">
            <img class="rounded w-36 h-36" src="{{ asset('images/silla.png') }}" alt="Extra large avatar">
        </div>
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-16 h-16 rounded-full ml-4" src="https://static.vecteezy.com/system/resources/previews/019/896/008/original/male-user-avatar-icon-in-flat-design-style-person-signs-illustration-png.png" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Tecnico: Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="flex justify-start max-w-md">
            <img class="rounded w-36 h-36" src="{{ asset('images/silla.png') }}" alt="Extra large avatar">
        </div>
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-16 h-16 rounded-full ml-4" src="https://static.vecteezy.com/system/resources/previews/019/896/008/original/male-user-avatar-icon-in-flat-design-style-person-signs-illustration-png.png" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Tecnico: Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <!-- card group 1 -->
        <div class="flex justify-start max-w-md">
            <img class="rounded w-36 h-36" src="{{ asset('images/silla.png') }}" alt="Extra large avatar">
        </div>
        <!-- card group 2 -->
        <div class="p-5 flex justify-end max-w-md">
            <div class="flex justify-start rounded-full h-full bg-[#99ccff] p-1 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center space-x-4 rounded-full">
                        <img class="w-16 h-16 rounded-full ml-4" src="https://static.vecteezy.com/system/resources/previews/019/896/008/original/male-user-avatar-icon-in-flat-design-style-person-signs-illustration-png.png" alt="">
                        <div class="font-medium dark:text-white">
                            <div>Tecnico: Gustavo Camacho</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Cliente: Nathaly Ortiz</div>
                        </div>
                        <div class="font-medium dark:text-white">
                            <div class="ml-10 mr-4">
                                <x-dropdown>
                                    <x-dropdown.header label="Servicio">
                                        <x-dropdown.item icon="cog" label="Detalle" />
                                    </x-dropdown.header>
                                </x-dropdown>
                            </div> 
                        </div>
                    </div>
            </div>
        </div>
    </div> --}}
</div>

