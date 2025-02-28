@php
use App\Models\FichaMedica;
@endphp
<div class="p-5">
    @livewire('notifications')

    <div class="p-5">
        {{-- Table de clientes registrados --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg  {{ $ocultar_table_cliente }}">
            <div class="flex justify-start mb-4">
                <input wire:model.live="buscar" type="search" id="search" name="buscar" class="border-b border-gray-200 py-2 text-sm rounded-full shadow-lg focus:ring-check-blue focus:border-check-blue" placeholder="Buscar cliente" autocomplete="off">
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-[#7898a5] dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cédula
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Teléfono
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ficha Médica
                        </th>
                        <th scope="col" class="px-6 py-3">

                        </th>
                        <th scope="col" class="px-6 py-3">

                        </th>
                        <th scope="col" class="px-6 py-3">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item->nombre.' '.$item->apellido }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $item->cedula }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->email }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->telefono }}
                        </td>
                        @if(FichaMedica::where('cliente_id', $item->id)->first())
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <x-avatar sm label="EN" class="bg-green-600"/>
                        </th>
                        @else
                        <th scope="row" class="cursor-pointer px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <x-avatar sm label="EN" class="bg-gray-200" onclick="Livewire.dispatch('openModal', { component: 'modal-encuesta', arguments: { cliente: {{ $item->id }} }})"/>
                        </th>
                        @endif
                        <td class="px-6 py-4 text-center items-center">
                            <span
                            onclick="Livewire.dispatch('openModal', { component: 'modal-clientes', arguments: { cliente: {{ $item->id }} }})"
                            class="cursor-pointer bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
                            Asignar
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center items-center">
                            <span
                            onclick="Livewire.dispatch('openModal', { component: 'modal-editar-cliente', arguments: { cliente: {{ $item->id }} }})"
                            class="cursor-pointer bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
                            Editar
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center items-center cursor-pointer">
                            @if($item->control == 1)
                            <svg class="w-6 h-6 text-green-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.133 12.632v-1.8a5.406 5.406 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V3.1a1 1 0 0 0-2 0v2.364a.955.955 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C6.867 15.018 5 15.614 5 16.807 5 17.4 5 18 5.538 18h12.924C19 18 19 17.4 19 16.807c0-1.193-1.867-1.789-1.867-4.175ZM6 6a1 1 0 0 1-.707-.293l-1-1a1 1 0 0 1 1.414-1.414l1 1A1 1 0 0 1 6 6Zm-2 4H3a1 1 0 0 1 0-2h1a1 1 0 1 1 0 2Zm14-4a1 1 0 0 1-.707-1.707l1-1a1 1 0 1 1 1.414 1.414l-1 1A1 1 0 0 1 18 6Zm3 4h-1a1 1 0 1 1 0-2h1a1 1 0 1 1 0 2ZM8.823 19a3.453 3.453 0 0 0 6.354 0H8.823Z"/>
                              </svg>

                              @endif
                              @if($item->control == null)
                            <svg wire:click=control({{ $item->id }}) class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.464V3.099m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C19 17.4 19 18 18.462 18H5.538C5 18 5 17.4 5 16.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.464ZM6 5 5 4M4 9H3m15-4 1-1m1 5h1M8.54 18a3.48 3.48 0 0 0 6.92 0H8.54Z"/>
                              </svg>
                              @endif
                            </td>
                    </t>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginacion --}}
        <div class="bg-white px-4 py-3 mt-4 items-center justify-between border-t border-gray-200 sm:px-6 {{ $ocultar_table_cliente }}">
            {{-- Paginacion --}}
            {{ $data->links() }}
        </div>
    </div>

    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    <x-menu_table/>
</div>
