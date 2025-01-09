<div>

    <x-filament::section class="border" icon="heroicon-m-presentation-chart-bar" icon-color="colorOne" collapsible>

        <x-slot name="heading" class="text-[#D9C3C1]">
            VENTA NETA
        </x-slot>

        @livewire('table-venta')



        {{-- Content --}}
    </x-filament::section>


    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 mb-4 mt-4 gap-4">
        <x-filament::section class="border" icon="heroicon-s-swatch" icon-color="colorOne" collapsible>
            <x-slot name="heading" class="text-[#D9C3C1]">
                SERVICIOS
            </x-slot>

            @livewire('table-venta-servicio')

            {{-- Content --}}
        </x-filament::section>

        <x-filament::section class="border" icon="heroicon-m-shopping-cart" icon-color="colorOne" collapsible>

            <x-slot name="heading" class="text-[#D9C3C1]">
                PRODUCTOS
            </x-slot>

            @livewire('table-venta-producto')


            {{-- Content --}}
        </x-filament::section>

    </div>



    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    <x-menu_table />
</div>

