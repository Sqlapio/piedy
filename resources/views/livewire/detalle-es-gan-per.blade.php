<div>
    @livewire('notifications')
    <div class="px-10 py-4">
        <div class="mb-10">
            <div class="flex">
                <h1 class="text-xl mb-6 font-extrabold text-[#7898a5] uppercase">ESTADO DE GANACIAS Y PERDIDAS</h1>
            </div>
        </div>

        <div class="mt-8">
            <div class="relative overflow-x-auto shadow-[0px_0px_0px_1px_rgba(0,0,0,0.06),0px_1px_1px_-0.5px_rgba(0,0,0,0.06),0px_3px_3px_-1.5px_rgba(0,0,0,0.06),_0px_6px_6px_-3px_rgba(0,0,0,0.06),0px_12px_12px_-6px_rgba(0,0,0,0.06),0px_24px_24px_-12px_rgba(0,0,0,0.06)] sm:rounded-lg">
                @livewire('table-detalle-es-gan-per')
            </div>
        </div>
    </div>

    {{-- div para separacion --}}
    <div class="w-full h-28"></div>

</div>
