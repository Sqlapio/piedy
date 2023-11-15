<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('actualiza_password') }}">
            @csrf

            <div class="block">
                <label class="opacity-60 mb-1 mt-4 block text-sm font-medium text-italblue">Email</label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            </div>
            <div class="block">
                <label class="opacity-60 mb-1 mt-4 block text-sm font-medium text-italblue">Nueva contraseña</label>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus />
            </div>
            <div class="block">
                <label class="opacity-60 mb-1 mt-4 block text-sm font-medium text-italblue">Repita su contraseña</label>
                <x-input id="password_two" class="block mt-1 w-full" type="password" name="password_two" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button  type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                    Actualizar
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
