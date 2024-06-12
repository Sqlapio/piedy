<div>
    <div class="px-10 py-4">
        <div class="mb-10">
            <div class="flex">
                <h1 class="text-md mb-4 font-bold text-[#bd9c95] uppercase">Rango de Fecha</h1>
            </div>
            <div class="grid md:grid-cols-4 sm:gap-4 md:gap-4">
                <div>
                    <x-datetime-picker id="min-max-times-input" without-timezone label="Desde:" placeholder="desde" wire:model.defer="desde" min-time="08:00" max-time="23:00" parse-format="YYYY-MM-DD HH:mm:ss" />
                </div>
                <div>
                    <x-datetime-picker id="min-max-times-input" without-timezone label="Hasta:" placeholder="hasta" wire:model.defer="hasta" min-time="08:00" max-time="23:00" parse-format="YYYY-MM-DD HH:mm:ss" />
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div class="flex justify-between">
                <div>
                    <h1 class="text-md mb-6 font-bold text-[#bd9c95] uppercase">Tabla de asignaciones y deducciones</h1>
                </div>
                <div>
                    <x-button icon="check" positive label="cargar nomina" wire:click="store" class="uppercase text-xs font-bold" />
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-center">
                    <thead class="text-xs text-black font-bold uppercase bg-[#bc9b94]">
                        <tr>
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
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($data); $i++) <tr class="bg-[#e4dcdc] border-b border-white">
                            <th scope="row" class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-white">
                                {{ $data[$i]->name }}
                            </th>
                            <td class="px-6 py-4">
                                <div class="">
                                    <x-input right-icon="claculator" wire:change="conver_asignacion_dolares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="asignacion_dolares.{{ $data[$i]->id }}" type="email" />
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="">
                                    <x-input right-icon="claculator" wire:change="conver_asignacion_bolivares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="asignacion_bolivares.{{ $data[$i]->id }}" type="email" />
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="">
                                    <x-input right-icon="claculator" wire:change="conver_deduccion_dolares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="deduccion_dolares.{{ $data[$i]->id }}" type="email" />
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="">
                                    <x-input right-icon="claculator" wire:change="conver_deduccion_bolivares({{ $data[$i]->id }})" id="{{ $data[$i]->id }}" wire:model="deduccion_bolivares.{{ $data[$i]->id }}" type="email" />
                                </div>
                            </td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="px-10 py-4">
        @livewire('table-nom-manicurista')
    </div>
</div>

