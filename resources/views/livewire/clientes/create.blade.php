<div>
    <div class="flex items-center justify-center">
        <form wire:submit="create">
            {{ $this->form }}
            <button type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                Guardar
            </button>
        </form>
    </div>
    <div class="flex w-screem"> 
        {{ $this->table }}
    </div>
    <x-filament-actions::modals />
</div>

