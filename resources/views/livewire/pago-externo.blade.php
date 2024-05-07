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
            <div class="flex justify-between">
                <div>
                    <label class="opacity-60 mb-1 mt-4 block text-sm font-medium text-italblue {{ $atr_label }}">Código GiftCard</label>
                </div>
                <div>
                    <label wire:click='input_pgc' class="cursor-pointer opacity-60 mb-1 mt-4 block text-sm font-medium text-green-600">Nro. PGC</label>
                </div>
            </div>
            <x-input wire:model.live='barcode' id="barcode" class="block mt-1 w-full {{ $atr_input }}" type="text" name="barcode" autofocus />

            <x-input wire:model.live='pgc' id="pgc" class="block mt-1 w-full {{$atr_pgc}}" type="text" name="pgc" autofocus />
            @if (session('activa'))
                <div class="flex justify-end alert alert-success text-xs text-green-800 font-bold text-left px-2">
                    <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/checkmark.gif') }}" alt="">
                    <div class="py-2">
                        {{ session('activa') }}
                    </div>
                </div>
            @endif
            @if (session('vencida'))
                <div class="flex justify-end alert alert-success text-xs text-red-800 font-bold text-left px-2">
                    <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/cancel.gif') }}" alt="">
                    <div class="py-2">
                        {{ session('vencida') }}
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="flex justify-end alert alert-success text-xs text-red-800 font-bold text-left px-2">
                    <img class="w-6 h-6 -ml-4 mt-1" src="{{ asset('images/cancel.gif') }}" alt="">
                    <div class="py-2">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Boton de validar gift card -->
            <div class="flex items-center justify-end mt-6 {{ $atr_btn_validar }}">
                <button wire:click.prevent='validar_gift' type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="validar_gift" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Validar GiftCard
                </button>
            </div>

            <!-- Boton de salir de la validacion gift card -->
            <div class="flex items-center justify-end mt-6 {{ $atr_btn_salir }}">
                <button wire:click.prevent='salir' type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="salir" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Salir
                </button>
            </div>

            <!-- Boton de facturar y terminar gift card -->
            <div class="flex items-center justify-end mt-6 gap-4 {{ $atr_acciones }}">
                <button wire:click.prevent='facturar' type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="facturar" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Facturar
                </button>
                <button wire:click.prevent='salir' type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="salir" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Salir
                </button>
            </div>

        <div class="{{ $atr_facturar }}">
            <div class="block">
                <label class="opacity-60 mb-1 mt-4 block text-sm font-medium text-italblue">Código de Asignación</label>
                <x-input wire:model='cod_asignacion' id="cod_asignacion" class="block mt-1 w-full" type="text" name="cod_asignacion" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button wire:click.prevent='facturtar_servicio' type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                    <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="facturtar_servicio" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Facturar Servicio
                </button>
            </div>
        </div>
    </x-authentication-card>
</div>
