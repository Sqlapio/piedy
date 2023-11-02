
    <div class="h-screen">
        @livewire('notifications')
        <h1 class="text-xl mb-4 font-bold text-[#bd9c95]">Citas del dia</h1>
        <div class="flex mt-10 mb-4 {{ $botton_agendar_cita }}">
            <button type="submit" wire:click.prevent="mostrar()" class="justify-start rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Agendar cita</span>
            </button>
        </div>

        {{-- tabla y boton del formulario de clientes --}}
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-4 mb-4 mt-8 {{ $ocultar }}">
            {{-- Descripcion --}}
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Fecha de cita</label>
                <x-input type="date" wire:model.defer="fecha" id="focus" class="focus:ring-check-blue focus:border-check-blue" />
            </div>
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Hora de cita</label>
                <x-time-picker placeholder="hora" format="12" interval="30" wire:model.defer="hora" />
            </div>
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Cliente</label>
                <x-select wire:model.defer="cliente_id" placeholder="Seleccion" :async-data="route('api.clientes')" option-label="nombre" option-value="id" />
            </div>
            <div class="p-2">
                <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Servicio</label>
                <x-select wire:model.defer="servicio_id" placeholder="Seleccion" :async-data="route('api.servicios')" option-label="descripcion" option-value="id" />
            </div>
        </div>

        <div class="flex justify-end p-2 mt-auto mb-4 {{ $ocultar }}">
            <button type="submit" wire:click.prevent="store()" class="justify-end rounded-md border border-transparent bg-[#7898a5] py-2 px-4 text-sm font-bold text-white shadow-sm hover:bg-check-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="store" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Guardar</span>
            </button>
        </div>

        {{-- --}}
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#4bbcf4] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">7:00am - 7:30am</h2>
                    </div>
                    <div class="flex flex-col justify-betweeCliente agendadon text-xs">
                        @foreach ($data as $item)
                        @if($item->hora == '07:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '07:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#61c0bf] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-white text-sm font-extrabold">8:00am - 8:30am</h2>
                    </div>
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data as $item)
                        @if($item->hora == '08:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '08:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#9ec0b8] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-white text-sm font-extrabold">9:00am - 9:30am</h2>
                    </div>
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data as $item)
                        @if($item->hora == '09:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '09:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#ffb6b9] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-white text-sm font-extrabold">10:00am - 10:30am</h2>
                    </div>
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data as $item)
                        @if($item->hora == '10:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '10:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{$item->hora}}am" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#4bbcf4] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">11:00am - 11:30am</h2>
                    </div>
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data as $item)
                        @if($item->hora == '11:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '11:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 2 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#61c0bf] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">12:00pm - 12:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '12:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '12:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 3 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#9ec0b8] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">1:00pm - 1:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '13:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '13:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 4 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#ffb6b9] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">2:00pm - 2:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '14:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '14:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#4bbcf4] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">3:00pm - 3:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '15:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '15:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 2 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#61c0bf] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">4:00pm - 4:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '16:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '16:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 3 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#9ec0b8] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">5:00pm - 5:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '17:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '17:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 4 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#ffb6b9] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">6:00pm - 6:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '18:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '18:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#4bbcf4] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">7:00pm - 7:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '19:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '19:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}am" class="mb-2 bg-[#0e9de5] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 2 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#61c0bf] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">8:00pm - 8:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '20:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '20:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#3d9897] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 3 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#9ec0b8] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">9:00pm - 9:30pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '21:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @if($item->hora == '21:30')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#70a296] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- card 4 -->
            <div class="p-2 max-w-sm">
                <div class="flex rounded-lg h-full bg-[#ffb6b9] p-2 flex-col shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                    <div class="flex items-center mb-3 p-2">
                        <div class="w-9 h-9 mr-3 inline-flex items-center justify-center  text-black flex-shrink-0">
                            <img src="{{ asset('images/calendar.png') }}" alt="">
                        </div>
                        <h2 class="text-white dark:text-black text-sm font-extrabold">10:00pm</h2>
                    </div>
                    <div class="flex flex-col justify-between">
                        @foreach ($data as $item)
                        @if($item->hora == '22:00')
                        <x-badge rounded label="{{ $item->cliente->nombre }} {{ $item->cliente->apellido }}: {{ $item->hora }}pm" class="mb-2 bg-[#ff6a70] text-white border-none" onclick="Livewire.dispatch('openModal', { component: 'asigna-servicio', arguments: { cita: {{ $item->id }} }})" />
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <x-menu_table />
    </div>



