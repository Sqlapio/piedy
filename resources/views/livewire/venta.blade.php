<div>
    <div class="border rounded-lg mb-5">
        @livewire('table-venta')
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 mb-4 mt-4 gap-4">
        <div class="border rounded-lg mb-5">
            @livewire('table-venta-servicio')
        </div>
        <div class="border rounded-lg mb-5">
            @livewire('table-venta-producto')
        </div>
    </div>



    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    <x-menu_table/>
</div>
