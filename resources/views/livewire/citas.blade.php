
    <div class="">
        @livewire('notifications')
        <h1 class="text-2xl mb-4 font-bold text-[#bd9c95]">Citas del dia</h1>

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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 ">

            <!-- card group 1 -->
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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
            <div class="p-2">
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

        <div class="w-full h-28"></div>

        {{-- Menu para table --}}
        <div class="fixed sm:z-0 md:z-0 lg:z-50 w-full h-16 mt-5 max-w-lg -translate-x-1/2 bg-white border border-gray-200 rounded-full bottom-4 left-1/2 dark:bg-gray-700 dark:border-gray-600">
            <div class="grid h-full max-w-lg grid-cols-7 mx-auto ">
                <button data-tooltip-target="tooltip-home" type="button" wire:click="inicio" class="inline-flex flex-col items-center justify-center px-5 rounded-l-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    <span class="sr-only">Inicio</span>
                </button>
                <div id="tooltip-home" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Inicio
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <button data-tooltip-target="tooltip-wallet" type="button" wire:click="productos" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                        <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                    </svg>
                    <span class="sr-only">Productos</span>
                </button>
                <div id="tooltip-wallet" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Productos
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <button data-tooltip-target="tooltip-products" type="button" wire:click="cabinas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 0H1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1Zm14 0h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1ZM5 14H1a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1Zm14 0h-4a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1ZM12 2H8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2Zm0 14H8a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2Zm-8-4V8a1 1 0 0 0-2 0v4a1 1 0 1 0 2 0Zm14 0V8a1 1 0 0 0-2 0v4a1 1 0 0 0 2 0Z"/>
                    </svg>
                    <span class="sr-only">Cabinas</span>
                </button>
                <div id="tooltip-products" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Cabinas
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <div class="flex items-center justify-center ">
                    <button data-tooltip-target="tooltip-new" type="button" wire:click.prevent="mostrar()" class="inline-flex items-center justify-center w-10 h-10 font-medium bg-[#7898a5] rounded-full hover:bg-[#5390a7] group focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800 shadow-[0_2.8px_2.2px_rgba(0,_0,_0,_0.034),_0_6.7px_5.3px_rgba(0,_0,_0,_0.048),_0_12.5px_10px_rgba(0,_0,_0,_0.06),_0_22.3px_17.9px_rgba(0,_0,_0,_0.072),_0_41.8px_33.4px_rgba(0,_0,_0,_0.086),_0_100px_80px_rgba(0,_0,_0,_0.12)]">
                        <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                        </svg>
                        <span class="sr-only">Agendar cita</span>
                    </button>
                </div>
                <div id="tooltip-new" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Agendar cita
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <button data-tooltip-target="tooltip-settings" type="button"  wire:click="citas" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path fill="currentColor" d="M6 1a1 1 0 0 0-2 0h2ZM4 4a1 1 0 0 0 2 0H4Zm7-3a1 1 0 1 0-2 0h2ZM9 4a1 1 0 1 0 2 0H9Zm7-3a1 1 0 1 0-2 0h2Zm-2 3a1 1 0 1 0 2 0h-2ZM1 6a1 1 0 0 0 0 2V6Zm18 2a1 1 0 1 0 0-2v2ZM5 11v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 11v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM10 15v-1H9v1h1Zm0 .01H9v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 15v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM15 11v-1h-1v1h1Zm0 .01h-1v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM5 15v-1H4v1h1Zm0 .01H4v1h1v-1Zm.01 0v1h1v-1h-1Zm0-.01h1v-1h-1v1ZM2 4h16V2H2v2Zm16 0h2a2 2 0 0 0-2-2v2Zm0 0v14h2V4h-2Zm0 14v2a2 2 0 0 0 2-2h-2Zm0 0H2v2h16v-2ZM2 18H0a2 2 0 0 0 2 2v-2Zm0 0V4H0v14h2ZM2 4V2a2 2 0 0 0-2 2h2Zm2-3v3h2V1H4Zm5 0v3h2V1H9Zm5 0v3h2V1h-2ZM1 8h18V6H1v2Zm3 3v.01h2V11H4Zm1 1.01h.01v-2H5v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H5v2h.01v-2ZM9 11v.01h2V11H9Zm1 1.01h.01v-2H10v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM9 15v.01h2V15H9Zm1 1.01h.01v-2H10v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H10v2h.01v-2ZM14 15v.01h2V15h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM14 11v.01h2V11h-2Zm1 1.01h.01v-2H15v2Zm1.01-1V11h-2v.01h2Zm-1-1.01H15v2h.01v-2ZM4 15v.01h2V15H4Zm1 1.01h.01v-2H5v2Zm1.01-1V15h-2v.01h2Zm-1-1.01H5v2h.01v-2Z"/>
                    </svg>
                    <span class="sr-only">Citas</span>
                </button>
                <div id="tooltip-settings" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Citas
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <button data-tooltip-target="tooltip-services" type="button"  wire:click="servicios" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M17 11h-2.722L8 17.278a5.512 5.512 0 0 1-.9.722H17a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM6 0H1a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V1a1 1 0 0 0-1-1ZM3.5 15.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM16.132 4.9 12.6 1.368a1 1 0 0 0-1.414 0L9 3.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z"/>
                    </svg>
                    <span class="sr-only">Servicios</span>
                </button>
                <div id="tooltip-services" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Servicios
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <button data-tooltip-target="tooltip-profile" type="button"  wire:click="clientes"  class="inline-flex flex-col items-center justify-center px-5 rounded-r-full hover:bg-gray-50 dark:hover:bg-gray-800 group">
                    <svg class="w-6 h-6 mb-1 text-[#cfb4b0] dark:text-gray-400 group-hover:text-[#be6a5d]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                    </svg>
                    <span class="sr-only">Clientes</span>
                </button>
                <div id="tooltip-profile" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Clientes
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
    </div>



