<div>
    <div class="p-5">
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Modulo de Servicios</h1>
        {{-- tabla --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-[#7898a5] dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Código de asignacion
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Fecha de atención
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total($)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only"></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item->id }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $item->cod_asignacion }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->cliente }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->fecha_venta }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ $item->total_USD }}
                        </td>
                        <td class="px-6 py-4">
                            <x-dropdown>
                                <x-dropdown.item icon="cog" label="Detalles" onclick="Livewire.dispatch('openModal', { component: 'historico-srv-detalle', arguments: { venta: {{ $item->id }} }})"/>
                            </x-dropdown>
                        </td>
                    </tr>
                    
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Paginacion --}}
        <div class="bg-white px-4 py-3 mt-4 items-center justify-between border-t border-gray-200 sm:px-6">
            {{-- Paginacion --}}
            {{ $data->links() }}
        </div> 
        
    </div>
    <x-menu_empleado_table></x-menu_empleado_table>
</div>

