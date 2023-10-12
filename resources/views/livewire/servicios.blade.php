<div>
    <div class="p-5">
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Modulo de Servicios</h1>
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
                                Codigo de Servicio
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white font-extrabold">
                                Descripcion
                            </th>
    
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-r-lg font-extrabold">
                                Costo
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-sm text-left rtl:text-right text-white rounded-r-lg font-extrabold">
                                Duracion
                            </th>
    
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900 rounded-lg">
                        @foreach ($data as $item)
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->id }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->cod_servicio }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->descripcion }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">${{ $item->costo }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap rounded-lg">{{ $item->duracion_max }} Minutos</td>
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
