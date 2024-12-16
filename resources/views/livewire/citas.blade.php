
    <div class="p-5">
        @livewire('notifications')
        <h1 class="text-2xl mb-4 font-bold text-[#bd9c95]">Agenda de Citas</h1>
            <div class="p-2 flex justify-between items-center">
                <div class="">
                  <x-select wire:change="$emit('selected', $event.target.value)" wire:model.live="mes" placeholder="Seleccion" :async-data="route('api.meses')" option-label="mes" option-value="numero" />
                </div>
                <div class="flex flex-row gap-2">
                    <ul class="flex border border-[#D9C3C1] bg-[#f1f1f1] rounded-xl">
                        <li>
                            <input type="radio" id="dia" wire:model.live="opcion" value="dia" class="hidden peer" required />
                            <label for="dia" class="inline-flex items-center justify-between p-2 m-1 text-[#7B95A6]  rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-[#7B9EA6] peer-checked:text-white peer-checked:font-extrabold peer-checked:shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                                <div class="block px-4">
                                    <div class="w-full text-md font-semibold">Día</div>
                                </div>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="semana" wire:model.live="opcion" value="semana" class="hidden peer" required />
                            <label for="semana" class="inline-flex items-center justify-between p-2 m-1 text-[#7B95A6] rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-[#7B9EA6] peer-checked:text-white peer-checked:font-extrabold peer-checked:shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                                <div class="block px-4">
                                    <div class="w-full text-md font-semibold">Semana</div>
                                </div>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="mes" wire:model.live="opcion" value="mes" class="hidden peer">
                            <label for="mes" class="inline-flex items-center justify-between p-2 m-1 text-[#7B95A6]  rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-[#7B9EA6] peer-checked:text-white peer-checked:font-extrabold peer-checked:shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                                <div class="block px-4">
                                    <div class="w-full text-md font-semibold">Mes</div>
                                </div>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        {{-- Citas agendadas --}}
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-7 xl:grid-cols-7 {{ $opcion == 'mes' || $opcion == 'semana' ? '' : 'hidden'}}">
            @foreach ($array as $key => $item)
            <div class="flex rounded-lg {{ $largo }} p-2 flex-col border border-[#D9C3C1] bg-[#F2F2F2]" >
                <div class="flex items-center mb-1 p-2 rounded-lg bg-[#7B9EA6] shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                    <h2 class="text-white text-sm font-bold cursor-pointer" wire:click="mountAction('create', { id: {{$key}} , mes: {{$mes}} })">{{ $item }}</h2>
                </div>
                <x-filament-actions::modals />
                <div class="flex rounded-lg {{ $scroll }} flex-col overflow-y-auto">
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data_citas as $items)
                            <div class="max-w-md space-y-2 text-gray-700 list-inside dark:text-gray-400">
                                @if($items->fecha == $item )
                                    <li class=" flex justify-between items-center p-1 text-2xs border text-gray-700 font-extrabold rounded-lg bg-[#D9C3C1]" >
                                        <div class="hover:flex flex-col group">
                                            <span class="hidden group-hover:block line-clamp-1 uppercase">{{ $items->empleado_id == null ? '.....' : $items->empleado->name }}</span>
                                            <span class="hidden group-hover:block line-clamp-1">{{ $items->servicio_id == null ? '.....' : $items->servicio->descripcion  }}</span>
                                            <div class="flex justify-start items-center">
                                                @if($items->responsable == 'PiedyBot')
                                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M12 2a7 7 0 0 0-7 7 3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1a1 1 0 0 0 1-1V9a5 5 0 1 1 10 0v7.083A2.919 2.919 0 0 1 14.083 19H14a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1a2 2 0 0 0 1.732-1h.351a4.917 4.917 0 0 0 4.83-4H19a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3 7 7 0 0 0-7-7Zm1.45 3.275a4 4 0 0 0-4.352.976 1 1 0 0 0 1.452 1.376 2.001 2.001 0 0 1 2.836-.067 1 1 0 1 0 1.386-1.442 4 4 0 0 0-1.321-.843Z" clip-rule="evenodd"/>
                                                  </svg>
                                                @endif
                                                <span class="line-clamp-1">{{ $items->cliente }}</span>
                                            </div>
                                            <span>Hora: {{$items->hora}}</span>
                                        </div>
                                        <div class="text-black">
                                            @if($items->empleado_id == null)
                                                <x-filament-actions::group
                                                    :actions="[
                                                        ($this->asignarAction)(['cita' => $items->id]),
                                                        ($this->eliminarAction)(['cita' => $items->id]),
                                                        ($this->recordarAction)(['cita' => $items->id])
                                                    ]"
                                                    icon="heroicon-m-ellipsis-vertical"
                                                    color="colorOne"
                                                />
                                            @else
                                                <x-filament-actions::group
                                                    :actions="[
                                                        ($this->activarAction)(['cita' => $items->id]),
                                                        ($this->eliminarAction)(['cita' => $items->id]),
                                                        ($this->recordarAction)(['cita' => $items->id])
                                                    ]"
                                                    icon="heroicon-m-ellipsis-vertical"
                                                    color="colorOne"
                                                />
                                            @endif
                                        </div>
                                    </li>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Horas del dia --}}
        <div class="flex justify-start items-center overflow-y-auto {{ $opcion == 'dia' ? '' : 'hidden'}}">
            @foreach ($horas as $hora)
            <div class="flex rounded-lg w-80 h-screen p-2 flex-col border border-[#D9C3C1] bg-[#F2F2F2]" >
                <div class="flex items-center mb-1 p-2 w-48 rounded-lg text-white bg-[#7B9EA6] shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                    <h2 class="text-white text-sm font-bold cursor-pointer">{{date("h:i a", strtotime($hora->hora))}}</h2>
                </div>
                <div class="flex rounded-lg flex-col zona">
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data_citas_dia as $items)
                            <div class="w-full space-y-2 text-gray-700 list-inside dark:text-gray-400">
                                @if(date("h:i a", strtotime($items->hora)) == date("h:i a", strtotime($hora->hora)) )
                                    <li class="flex justify-between items-center p-1 text-2xs border text-gray-600 font-extrabold rounded-lg bg-[#D9C3C1]" >
                                        <div class="hover:flex flex-col group">
                                            <span class="hidden group-hover:block line-clamp-1 uppercase">{{ $items->empleado_id == null ? '.....' : $items->empleado->name }}</span>
                                            <span class="hidden group-hover:block line-clamp-1">{{ $items->servicio_id == null ? '.....' : $items->servicio->descripcion  }}</span>
                                            <span class="line-clamp-1">{{ $items->cliente }}</span>
                                            <span>Hora: {{$items->hora}}</span>
                                        </div>
                                        <div class="text-black">
                                            @if($items->empleado_id == null)
                                                <x-filament-actions::group
                                                    :actions="[
                                                        ($this->asignarAction)(['cita' => $items->id]),
                                                        ($this->eliminarAction)(['cita' => $items->id])
                                                    ]"
                                                    icon="heroicon-m-ellipsis-vertical"
                                                    color="colorOne"
                                                />
                                            @else
                                                <x-filament-actions::group
                                                    :actions="[
                                                        ($this->activarAction)(['cita' => $items->id]),
                                                        ($this->eliminarAction)(['cita' => $items->id])
                                                    ]"
                                                    icon="heroicon-m-ellipsis-vertical"
                                                    color="colorOne"
                                                />
                                            @endif
                                        </div>
                                    </li>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- div para separacion ene le diseno --}}
        <div class="w-full h-28"></div>

        <x-menu_table/>
    </div>
