
    <div class="p-5">
        @livewire('notifications')
        <h1 class="text-2xl mb-4 font-bold text-[#bd9c95]">Agenda de Citas</h1>
            <div class="p-2 flex justify-between items-center">
                {{-- <h1 class="text-lg font-bold leading-6 text-black uppercase">
                    {{ Carbon::parse(date('d-m-Y'))->isoFormat('dddd, D MMMM Y ') }}
                </h1> --}}
                <div class="">
                  <x-select wire:change="$emit('selected', $event.target.value)" wire:model.live="mes" placeholder="Seleccion" :async-data="route('api.meses')" option-label="mes" option-value="numero" />
                </div>
                {{-- {{ $opcion }} {{ $largo }} {{ $inicio }} {{ $fin }} {{ $mes }} --}}
                <div class="flex flex-row gap-2">
                    <ul class="flex border border-[#D9C3C1] bg-[#F2F2F2] rounded-xl">
                        <li>
                            <input type="radio" id="dia" wire:model.live="opcion" value="dia" class="hidden peer" required />
                            <label for="dia" class="inline-flex items-center justify-between p-2 m-1 text-[#7B95A6]  rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-[#7B9EA6] peer-checked:text-white peer-checked:font-extrabold peer-checked:shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                                <div class="block px-4">
                                    <div class="w-full text-md font-semibold">Día</div>
                                </div>
                                {{-- <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg> --}}
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="semana" wire:model.live="opcion" value="semana" class="hidden peer" required />
                            <label for="semana" class="inline-flex items-center justify-between p-2 m-1 text-[#7B95A6] rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-[#7B9EA6] peer-checked:text-white peer-checked:font-extrabold peer-checked:shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                                <div class="block px-4">
                                    <div class="w-full text-md font-semibold">Semana</div>
                                </div>
                                {{-- <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg> --}}
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="mes" wire:model.live="opcion" value="mes" class="hidden peer">
                            <label for="mes" class="inline-flex items-center justify-between p-2 m-1 text-[#7B95A6]  rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:bg-[#7B9EA6] peer-checked:text-white peer-checked:font-extrabold peer-checked:shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                                <div class="block px-4">
                                    <div class="w-full text-md font-semibold">Mes</div>
                                </div>
                                {{-- <svg class="w-5 h-5 ms-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg> --}}
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
                                    <li class=" group h-14 hover:-translate-y-1 hover:h-40 hover:bg-indigo-500 duration-300 flex justify-between items-center p-1 text-2xs border text-gray-700 font-extrabold rounded-lg bg-[#D9C3C1]" >
                                        <div class="flex flex-col">
                                            <span class="hidden group-hover:block line-clamp-1 uppercase">{{ $items->empleado_id == null ? '.....' : $items->empleado->name }}</span>
                                            <span class="hidden group-hover:block line-clamp-1">{{ $items->servicio_id }}</span>
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
                                        <div class="flex flex-col">
                                            {{-- <span class="line-clamp-1">{{ App\Models\User::find($items->empleado_id)->name != null ? App\Models\User::find($items->empleado_id)->name != null : 'No Asignado' }}</span> --}}
                                            <span class="line-clamp-1">{{ $items->empleado_id == null ? '.....' : $items->empleado->name }}</span>
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
