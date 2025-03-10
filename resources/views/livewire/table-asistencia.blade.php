<div class="p-2">
    @livewire('notifications')
    <div class="grid grid-cols-1 gap-2 p-4 mt-8">
        {{ $this->table }}
    </div>
    <div class="w-full h-28"></div>
    <x-menu_table />

</div>

