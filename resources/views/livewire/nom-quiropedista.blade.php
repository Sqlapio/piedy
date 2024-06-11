<div>
    <div class="p-5">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Quiropedista
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Asignaciones($)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Asignaciones(Bs.)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Deducciones($)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Deducciones(Bs.)
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cargar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < count($data); $i++)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $data[$i]->name }}
                        </th>
                        <td class="px-6 py-4">
                            <div class="">
                                <x-input right-icon="user" wire:change="conver_asignacion_dolares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="asignacion_dolares.{{ $data[$i]->id }}" type="email"/>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="">
                                <x-input right-icon="user" wire:change="conver_asignacion_bolivares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="asignacion_bolivares.{{ $data[$i]->id }}" type="email"/>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="">
                                <x-input right-icon="user" wire:change="conver_deduccion_dolares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="deduccion_dolares.{{ $data[$i]->id }}" type="email"/>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="">
                                <x-input right-icon="user" wire:change="conver_deduccion_bolivares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="deduccion_bolivares.{{ $data[$i]->id }}" type="email"/>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="">
            <x-button icon="check" positive label="Cargar" wire:click="store" />
        </div>
    </div>
</div>
