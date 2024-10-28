<div>
    <div class="border rounded-lg mb-5">

        {{ $this->table }}
    </div>

    <div class="border rounded-lg mb-5 mt-10">

        @livewire('tabla-facturas-multiples')
    </div>

    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    <x-menu_table/>
</div>
