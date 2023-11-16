<div>
    <div class="border rounded-lg mb-5">
        <p class="p-4 text-3xl font-bold text-[#bc9c95]">Historico de servicios</p>
        {{ $this->table }}
    </div>

    {{-- div para separacion --}}
    <div class="w-full h-20"></div>

    {{-- Menu para table --}}
    <x-menu_empleado_table></x-menu_empleado_table>
</div>
