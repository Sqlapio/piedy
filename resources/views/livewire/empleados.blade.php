<div>
    <div class="p-5">
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Modulo de Empleados</h1>
        {{-- tabla --}}
        <div class="tabla">
            {{-- Tabla de clientes --}}
            <div class="rounded-lg">
                <table class="w-full mt-2 rounded-lg">
                    <thead class="bg-[#7898a5]">
                        <tr>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-l-lg font-extrabold">
                                ID
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Empleado
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Teléfono
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-r-lg font-extrabold">
                                Disponibilidad
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-r-lg font-extrabold">
                                Aciones
                            </th>
    
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900 rounded-lg">
                        @foreach ($data as $item)
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->nombre.' '.$item->apellido }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->telefono }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">
                                @if($item->disponible == 1)
                                    <x-badge icon="check" positive lg label="Libre" />
                                @else
                                    <x-badge icon="check" negative lg label="Ocupado" />
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 rounded-lg">
                                <x-dropdown>
                                    <x-dropdown.item label="Asignar cliente" type="submit" wire:click="asigna_cliente({{ $item->id }}, '2')"/>
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
