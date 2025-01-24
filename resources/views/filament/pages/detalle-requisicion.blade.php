@php
use Filament\Facades\Filament;
@endphp

<div class="p-3">
    @livewire('modal-table-detalle-requisicion', [
    'codigo' => $record,
    'sucursal_id' => $sucursal
    ])
</div>





