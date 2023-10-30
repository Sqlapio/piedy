@php
use Carbon\Carbon;
$h = Carbon::now('America/Caracas')->format('h:i:s');
@endphp
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 mb-4 mt-4">
    {{-- Descripcion --}}
    @foreach ($data as $item)
    <div class="flex justify-between p-5">
        <div class="w-full" type="submit"  onclick="Livewire.dispatch('openModal', { component: 'detalle-asignacion', arguments: { venta: {{ $item->id }} }})">
            @if($item->status != 'cerrado')
            <div class="flex justify-start rounded-full bg-green-500 px-6 py-2 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center space-x-4 w-full">
                    @if (Str::contains($item->area_trabajo, 'c'))
                    <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                    @endif
                    @if (Str::contains($item->area_trabajo, 'm'))
                    <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/mesas.png') }}" alt="">
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-md font-extrabold text-white truncate dark:text-white">
                            {{ $item->empleado }}
                        </p>
                        <p class="text-sm text-white truncate dark:text-gray-400">
                            Cliente: {{ $item->cliente }}
                        </p>
                    </div>
                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                        <svg class="w-8 h-8 mr-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 8V4.5a3.5 3.5 0 1 0-7 0V8M8 12.167v3M2 8h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"/>
                          </svg>
                    </div>
                </div>
            </div>
            @else
            <div class="flex justify-start rounded-full bg-red-700 px-6 py-2 shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px]">
                <div class="flex items-center space-x-4 w-full">
                    @if (Str::contains($item->area_trabajo, 'c'))
                    <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/silla.png') }}" alt="">
                    @endif
                    @if (Str::contains($item->area_trabajo, 'm'))
                    <img class="w-20 h-20 rounded-full ml-4" src="{{ asset('images/mesas.png') }}" alt="">
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-md font-extrabold text-white truncate dark:text-white">
                            {{ $item->empleado }}
                        </p>
                        <p class="text-sm text-white truncate dark:text-gray-400">
                            Cliente: {{ $item->cliente }}
                        </p>
                    </div>
                    <div class="inline-flex items-center text-base font-bold text-white">
                        <svg class="w-8 h-8 mr-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 20">
                            <path d="M8 14.5a6.474 6.474 0 0 1 8-6.318V8a1 1 0 0 0-1-1h-2.5V4.5a4.5 4.5 0 1 0-9 0V7H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9.052A6.494 6.494 0 0 1 8 14.5Zm-2.5-10a2.5 2.5 0 1 1 5 0V7h-5V4.5Z"/>
                            <path d="M14.5 10a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Zm2.06 6.561a1 1 0 0 1-1.414 0l-1.353-1.354a1 1 0 0 1-.293-.707v-1.858a1 1 0 0 1 2 0v1.444l1.06 1.06a1.001 1.001 0 0 1 0 1.415Z"/>
                          </svg>
                                                    
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
    <x-menu_table />
</div>

