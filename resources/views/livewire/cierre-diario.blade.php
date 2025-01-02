<div>
    <div class="mt-10 pt-5">
        @livewire('notifications')
        <h1 class="text-xl mb-6 font-bold text-[#bd9c95] uppercase">Módulo de cierre por turno</h1>
    </div>

    <div class="border rounded-lg mb-5 mt-10">
        {{ $this->table }}
    </div>
        {{-- div para separacion ene le diseno --}}
        <div class="w-full h-28"></div>

        <x-menu_table/>
</div>

