<div>
    @livewire('notifications')
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <div class="block">
            <label class="opacity-60 mb-1 mt-4 block text-sm font-medium text-italblue">Cédula de Identidad</label>
            <x-input wire:model='cedula' id="cedula" class="block mt-1 w-full" type="text" name="cedula" required autofocus />
        </div>

        <div class="flex items-center justify-end mt-6">
            <button wire:click.prevent='validar_usuario' type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="validar_usuario" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Validar Usuario
            </button>
        </div>
    </x-authentication-card>
</div>

