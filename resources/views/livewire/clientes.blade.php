<div>
    <div class="p-5">
        @livewire('notifications')
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Modulo de Clientes</h1>
        {{-- <div class="py-5 mt-4">
            <div class="flex justify-between">
                <input wire:model="buscar" type="search" name="buscar" class="border-b border-gray-200 py-2 text-sm rounded-full sm:w-1/3 md:w-1/4 shadow-lg focus:ring-check-blue focus:border-check-blue" placeholder="Buscar..." autocomplete="off">
            </div>
        </div> --}}
        {{-- tabla y boton del formulario de clientes --}}
        <div class="bg-white rounded-xl {{ $ocultar_form_cliente }}">
            <div class="overflow-auto rounded-xl shadow-md md:block p-4">
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-6 gap-4 mb-4 mt-8">
                    {{-- Descripcion --}}
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Nombre</label>
                        <x-input icon="user" wire:model="nombre"  class="focus:ring-check-blue focus:border-check-blue valLetras"/>
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Apellido</label>
                        <x-input icon="user" wire:model="apellido"  class="focus:ring-check-blue focus:border-check-blue valLetras"/>
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Cedula</label>
                        <x-input icon="credit-card" wire:model="cedula"  class="focus:ring-check-blue focus:border-check-blue valLetras"/>
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Email</label>
                        <x-input icon="mail" wire:model="email" type="email"  class="focus:ring-check-blue focus:border-check-blue valLetras"/>
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Teléfono</label>
                        <x-input icon="phone" wire:model="telefono" class="focus:ring-check-blue focus:border-check-blue valLetras"/>
                    </div>
                    <div class="p-2">
                        <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Dirección</label>
                        <x-input icon="map" wire:model="direccion_corta"  class="focus:ring-check-blue focus:border-check-blue valLetras"/>
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

        <div class="tabla {{ $ocultar_table_cliente }}">
            {{-- boton anadir --}}
            <div class="flex justify-end p-2 mt-auto">
                <button type="submit" wire:click.prevent="ocultar_table()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>+ Añadir</span>
                </button>
            </div>
            {{-- Tabla de clientes --}}
            <div class="rounded-lg">
                <table class="w-full mt-2 rounded-lg">
                    <thead class="bg-[#7898a5]">
                        <tr>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-l-lg font-extrabold">
                                ID
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Cliente
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Cédula de identidad
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Email
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Teléfono
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-r-lg font-extrabold">
                                Dirección
                            </th>

                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-r-lg font-extrabold">
                                Acciones
                            </th>
    
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900 rounded-lg">
                        @foreach ($data as $item)
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->nombre.' '.$item->apellido }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->cedula }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->email }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->telefono }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->direccion_corta }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 rounded-lg">
                                <x-dropdown>
                                    <x-dropdown.item label="Asignar servicio" type="submit" wire:click="asigna_servicio({{ $item->id }}, '2')"/>
                                </x-dropdown>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Div para la paginacion --}}
            <div class="bg-white px-4 py-3 items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg">
                {{-- Paginacion --}}
                {{ $data->links() }}
            </div>
        </div>
        
    </div>
</div>
