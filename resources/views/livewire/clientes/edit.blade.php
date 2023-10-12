
<div>
    <div class="w-screen flex items-center justify-center">
        <form wire:submit="create">
            {{ $this->form }}
            <button type="submit">
                Guardar
            </button>
        </form>
        
    
        <x-filament-actions::modals />
    </div>
    <div class="w-screen flex items-center justify-center"> 
        {{ $this->table }}
    </div>
</div>
