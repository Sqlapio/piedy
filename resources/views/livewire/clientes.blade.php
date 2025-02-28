@php
use App\Models\FichaMedica;
@endphp
<div class="p-5">
    @livewire('notifications')

    <div class="p-5">
        @livewire('table-cliente')
    </div>

    {{-- div para separacion ene le diseno --}}
    <div class="w-full h-28"></div>

    <x-menu_table/> 
</div>
