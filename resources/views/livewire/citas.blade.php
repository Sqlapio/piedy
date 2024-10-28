@php
use App\Models\Cita;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
    $date = date('Y-m');
    $data_citas = Cita::where('status', 1)->where('fecha_formateada', 'like', '%'.$date.'%')->get();
    $datas = Trend::model(Cita::class)
            ->between(
                now()->startOfMonth()->month($mes),
                now()->endOfMonth()->month($mes),
            )
            ->perDay()
            ->count();
    $array = $datas->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray();
@endphp
    <div class="">
        @livewire('notifications')
        <h1 class="text-2xl mb-4 font-bold text-[#bd9c95]">Agenda del dia</h1>
            <div class="p-2 flex justify-between items-center">
                <h1 class="text-lg font-bold leading-6 text-black uppercase">
                    {{ Carbon::parse(date('d-m-Y'))->isoFormat('dddd, D MMMM Y ') }}
                </h1>
                  <div class="p-2">
                    <x-select wire:change="$emit('selected', $event.target.value)" wire:model.live="mes" placeholder="Seleccion" :async-data="route('api.meses')" option-label="mes" option-value="numero" />
                  </div>
            </div>

        {{-- Citas agendadas --}}
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-7 xl:grid-cols-7">
            @foreach ($array as $key => $item)
            <div class="flex rounded-lg h-44  p-2 flex-col border-gray-400 border" >
                <div class="flex items-center mb-1 p-2">
                    <h2 class="text-black dark:text-black text-xs font-bold cursor-pointer" wire:click="$dispatch('openModal', { component: 'modal-agenda', arguments: { fecha: {{ $key }}, mes: {{ $mes }} }})">{{ $item }}</h2>
                </div>
                <div class="flex rounded-lg h-44 flex-col zona overflow-x-auto overflow-y-auto">
                    <div class="flex flex-col justify-between text-xs">
                        @foreach ($data_citas as $items)
                        <div class="max-w-md space-y-1 text-gray-700 list-inside dark:text-gray-400">
                                    @if($items->fecha == $item )
                                        <li class="flex items-center p-1" wire:click="$dispatch('openModal', { component: 'modal-cita', arguments: { cita: {{ $items->id }} }})">
                                            <svg  class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                            </svg>
                                            {{ $items->cliente }} <br> Hora: {{$items->hora}}
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

        <script>
            const columns = document.querySelectorAll(".zona");
            document.addEventListener("dragstart", (e) => {
            e.target.classList.add("dragging");
            });

            document.addEventListener("dragend", (e) => {
            e.target.classList.remove("dragging");
            });

            columns.forEach((item) => {
            item.addEventListener("dragover", (e) => {
            const dragging = document.querySelector(".dragging");
            const applyAfter = getNewPosition(item, e.clientY);

            if (applyAfter) {
            console.log('estoy aqui');
            applyAfter.insertAdjacentElement("afterend", dragging);
            } else {

            item.prepend(dragging);
            }
            });
            });

            function getNewPosition(column, posY) {
            console.log('estoy aqui2');
            const cards = column.querySelectorAll(".item:not(.dragging)");
            let result;

            for (let refer_card of cards) {
            const box = refer_card.getBoundingClientRect();
            const boxCenterY = box.y + box.height / 1;

            if (posY >= boxCenterY) result = refer_card;
            }

            return result;
            }

        </script>
    </div>
