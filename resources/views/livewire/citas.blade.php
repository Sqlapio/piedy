
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
            <div class="flex rounded-lg {{ $largo }} p-2 flex-col border border-[#D9C3C1] bg-[#ffffff]" >
                <div class="flex items-center mb-1 p-2 rounded-lg bg-[#7B9EA6] shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)]">
                    <h2 class="text-white text-sm font-bold cursor-pointer" wire:click="mountAction('create', { id: {{$key}} , mes: {{$mes}} })">{{ $item }}</h2>
                </div>
                <x-filament-actions::modals />
                <div class="flex rounded-lg {{ $scroll }} flex-col overflow-y-auto">
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data_citas as $items)
                            <div class="max-w-md space-y-2 text-gray-700 list-inside dark:text-gray-400">
                                @if($items->fecha == $item )
                                    <li class=" flex justify-between items-center p-1 text-2xs border text-gray-700 font-extrabold rounded-lg bg-[#D9C3C1] " >
                                        <div class="hover:flex flex-col group p-1">
                                            @if($items->confirmacion == 1)
                                                <div class="flex justify-start items-center py-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-green-700">
                                                        <path d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
                                                        <path fill-rule="evenodd" d="M12 2.25A6.75 6.75 0 0 0 5.25 9v.75a8.217 8.217 0 0 1-2.119 5.52.75.75 0 0 0 .298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 1 0 7.48 0 24.583 24.583 0 0 0 4.83-1.244.75.75 0 0 0 .298-1.205 8.217 8.217 0 0 1-2.118-5.52V9A6.75 6.75 0 0 0 12 2.25ZM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 0 0 4.496 0l.002.1a2.25 2.25 0 1 1-4.5 0Z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="ml-1 uppercase text-green-700">Confirmada</span>
                                                </div>
                                            @endif
                                            <span class="hidden group-hover:block line-clamp-1 uppercase">{{ $items->empleado_id == null ? '.....' : $items->empleado->name }}</span>
                                            <span class="hidden group-hover:block line-clamp-1">{{ $items->servicio_id == null ? '.....' : $items->servicio->descripcion  }}</span>
                                            <div class="flex justify-start items-center">
                                                @if($items->responsable == 'PiedyBot')
                                                {{-- <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M12 2a7 7 0 0 0-7 7 3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1a1 1 0 0 0 1-1V9a5 5 0 1 1 10 0v7.083A2.919 2.919 0 0 1 14.083 19H14a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1a2 2 0 0 0 1.732-1h.351a4.917 4.917 0 0 0 4.83-4H19a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3 7 7 0 0 0-7-7Zm1.45 3.275a4 4 0 0 0-4.352.976 1 1 0 0 0 1.452 1.376 2.001 2.001 0 0 1 2.836-.067 1 1 0 1 0 1.386-1.442 4 4 0 0 0-1.321-.843Z" clip-rule="evenodd"/>
                                                  </svg> --}}
                                                  <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 32 32" viewBox="0 0 32 32" id="ChatBot" class="w-9 h-auto">
                                                    <path fill="#382626" d="M12.5 12.521c-.276 0-.5-.224-.5-.5v-.5c0-.276.224-.5.5-.5s.5.224.5.5v.5C13 12.297 12.776 12.521 12.5 12.521zM19.5 12.521c-.276 0-.5-.224-.5-.5v-.5c0-.276.224-.5.5-.5s.5.224.5.5v.5C20 12.297 19.776 12.521 19.5 12.521zM15 12.521h2c.276 0 .508.23.418.491-.204.587-.761 1.009-1.418 1.009s-1.214-.422-1.418-1.009C14.492 12.75 14.724 12.521 15 12.521z" class="color263238 svgShape"></path>
                                                    <path fill="#644545" d="M21.5 18.021h-11c-2.481 0-4.5-2.019-4.5-4.5v-6c0-2.481 2.019-4.5 4.5-4.5.276 0 .5.224.5.5s-.224.5-.5.5c-1.93 0-3.5 1.57-3.5 3.5v6c0 1.93 1.57 3.5 3.5 3.5h11c1.93 0 3.5-1.57 3.5-3.5v-6c0-1.93-1.57-3.5-3.5-3.5h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c2.481 0 4.5 2.019 4.5 4.5v6C26 16.002 23.981 18.021 21.5 18.021zM6.399 27.496c-.228 0-.434-.157-.487-.388-.025-.109-.583-2.729 2.407-7.373.252-.397.541-.746.88-1.064.202-.187.519-.178.707.023s.178.518-.023.707c-.277.259-.513.544-.721.872-1.929 2.997-2.27 5.049-2.302 6.004.448-.223 1.132-.646 1.953-1.42.202-.19.518-.18.707.021.189.201.18.518-.021.707-1.704 1.606-2.941 1.888-2.994 1.899C6.47 27.492 6.435 27.496 6.399 27.496zM25.601 27.496c-.035 0-.07-.003-.106-.011-.05-.011-1.242-.282-2.899-1.812-.203-.187-.215-.503-.028-.707.187-.203.504-.215.707-.028.783.723 1.435 1.124 1.867 1.339-.032-.952-.372-3.004-2.301-6.001-.211-.332-.448-.618-.727-.878-.202-.188-.212-.505-.024-.707.188-.202.506-.213.707-.024.341.318.631.668.886 1.07 2.989 4.642 2.431 7.261 2.406 7.371C26.034 27.339 25.829 27.496 25.601 27.496z" class="color455a64 svgShape"></path>
                                                    <path fill="#644545" d="M15.996 31.015c-.527 0-1.054-.165-1.495-.496-1.39-1.046-3.886-3.48-4.986-8.036-.065-.268.1-.539.369-.603.265-.065.539.1.603.369 1.027 4.252 3.333 6.506 4.616 7.471.537.403 1.258.402 1.793-.004 1.282-.971 3.586-3.231 4.617-7.467.065-.268.335-.434.604-.368.269.065.433.336.368.604-1.104 4.537-3.597 6.977-4.985 8.027C17.057 30.848 16.526 31.015 15.996 31.015zM12.5 4.021c-.276 0-.5-.224-.5-.5v-2c0-.276.224-.5.5-.5s.5.224.5.5v2C13 3.797 12.776 4.021 12.5 4.021zM19.5 4.021c-.276 0-.5-.224-.5-.5v-2c0-.276.224-.5.5-.5s.5.224.5.5v2C20 3.797 19.776 4.021 19.5 4.021z" class="color455a64 svgShape"></path>
                                                    <g fill="#060000" class="color000000 svgShape">
                                                      <path fill="#644545" d="M20.5,16.02h-9c-1.378,0-2.5-1.122-2.5-2.5v-4c0-1.378,1.122-2.5,2.5-2.5h9c1.378,0,2.5,1.122,2.5,2.5v4
                                                                      C23,14.898,21.878,16.02,20.5,16.02z M11.5,8.02c-0.827,0-1.5,0.673-1.5,1.5v4c0,0.827,0.673,1.5,1.5,1.5h9
                                                                      c0.827,0,1.5-0.673,1.5-1.5v-4c0-0.827-0.673-1.5-1.5-1.5H11.5z" class="color455a64 svgShape"></path>
                                                    </g>
                                                    <g fill="#060000" class="color000000 svgShape">
                                                      <path fill="#644545" d="M17.5,4.021h-5c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h5c0.276,0,0.5,0.224,0.5,0.5
                                                                      S17.776,4.021,17.5,4.021z" class="color455a64 svgShape"></path>
                                                    </g>
                                                    <g fill="#060000" class="color000000 svgShape">
                                                      <path fill="#382626" d="M21.5 18.021h-11c-2.481 0-4.5-2.019-4.5-4.5v-6c0-2.481 2.019-4.5 4.5-4.5.276 0 .5.224.5.5s-.224.5-.5.5c-1.93 0-3.5 1.57-3.5 3.5v6c0 1.93 1.57 3.5 3.5 3.5h11c1.93 0 3.5-1.57 3.5-3.5v-6c0-1.93-1.57-3.5-3.5-3.5h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c2.481 0 4.5 2.019 4.5 4.5v6C26 16.002 23.981 18.021 21.5 18.021zM6.399 27.496c-.228 0-.434-.157-.487-.388-.025-.109-.583-2.729 2.407-7.373.252-.397.541-.746.88-1.064.202-.187.519-.178.707.023s.178.518-.023.707c-.277.259-.513.544-.721.872-1.929 2.997-2.27 5.049-2.302 6.004.448-.223 1.132-.646 1.953-1.42.202-.19.518-.18.707.021.189.201.18.518-.021.707-1.704 1.606-2.941 1.888-2.994 1.899C6.47 27.492 6.435 27.496 6.399 27.496zM25.601 27.496c-.035 0-.07-.003-.106-.011-.05-.011-1.242-.282-2.899-1.812-.203-.187-.215-.503-.028-.707.187-.203.504-.215.707-.028.783.723 1.435 1.124 1.867 1.339-.032-.952-.372-3.004-2.301-6.001-.211-.332-.448-.618-.727-.878-.202-.188-.212-.505-.024-.707.188-.202.506-.213.707-.024.341.318.631.668.886 1.07 2.989 4.642 2.431 7.261 2.406 7.371C26.034 27.339 25.829 27.496 25.601 27.496z" class="color263238 svgShape"></path>
                                                      <path fill="#382626" d="M15.996 31.015c-.527 0-1.054-.165-1.495-.496-1.39-1.046-3.886-3.48-4.986-8.036-.065-.268.1-.539.369-.603.265-.065.539.1.603.369 1.027 4.252 3.333 6.506 4.616 7.471.537.403 1.258.402 1.793-.004 1.282-.971 3.586-3.231 4.617-7.467.065-.268.335-.434.604-.368.269.065.433.336.368.604-1.104 4.537-3.597 6.977-4.985 8.027C17.057 30.848 16.526 31.015 15.996 31.015zM12.5 4.021c-.276 0-.5-.224-.5-.5v-2c0-.276.224-.5.5-.5s.5.224.5.5v2C13 3.797 12.776 4.021 12.5 4.021zM19.5 4.021c-.276 0-.5-.224-.5-.5v-2c0-.276.224-.5.5-.5s.5.224.5.5v2C20 3.797 19.776 4.021 19.5 4.021z" class="color263238 svgShape"></path>
                                                      <g fill="#060000" class="color000000 svgShape">
                                                        <path fill="#382626" d="M20.5,16.02h-9c-1.378,0-2.5-1.122-2.5-2.5v-4c0-1.378,1.122-2.5,2.5-2.5h9c1.378,0,2.5,1.122,2.5,2.5v4
                                                                      C23,14.898,21.878,16.02,20.5,16.02z M11.5,8.02c-0.827,0-1.5,0.673-1.5,1.5v4c0,0.827,0.673,1.5,1.5,1.5h9
                                                                      c0.827,0,1.5-0.673,1.5-1.5v-4c0-0.827-0.673-1.5-1.5-1.5H11.5z" class="color263238 svgShape"></path>
                                                      </g>
                                                      <g fill="#060000" class="color000000 svgShape">
                                                        <path fill="#382626" d="M17.5,4.021h-5c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h5c0.276,0,0.5,0.224,0.5,0.5
                                                                      S17.776,4.021,17.5,4.021z" class="color263238 svgShape"></path>
                                                      </g>
                                                    </g>
                                                  </svg>
                                                @endif
                                                <span class="line-clamp-1 ml-1">{{ $items->cliente }}</span>
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
                                        <div class="hover:flex flex-col group p-1">
                                            @if($items->confirmacion == 1)
                                                <div class="flex justify-start items-center py-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-green-700">
                                                        <path d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
                                                        <path fill-rule="evenodd" d="M12 2.25A6.75 6.75 0 0 0 5.25 9v.75a8.217 8.217 0 0 1-2.119 5.52.75.75 0 0 0 .298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 1 0 7.48 0 24.583 24.583 0 0 0 4.83-1.244.75.75 0 0 0 .298-1.205 8.217 8.217 0 0 1-2.118-5.52V9A6.75 6.75 0 0 0 12 2.25ZM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 0 0 4.496 0l.002.1a2.25 2.25 0 1 1-4.5 0Z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="ml-1 uppercase text-green-700">Confirmada</span>
                                                </div>
                                            @endif
                                            <span class="hidden group-hover:block line-clamp-1 uppercase">{{ $items->empleado_id == null ? '.....' : $items->empleado->name }}</span>
                                            <span class="hidden group-hover:block line-clamp-1">{{ $items->servicio_id == null ? '.....' : $items->servicio->descripcion  }}</span>
                                            <span class="line-clamp-1 ml-1">{{ $items->cliente }}</span>
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

        {{-- div para separacion ene le diseno --}}
        <div class="w-full h-28"></div>

        <x-menu_table/>
    </div>
