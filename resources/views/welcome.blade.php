<x-guest-layout>
    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2">
        <div class="min-[250px]:hidden px-6 my-auto md:block lg:block">
            <video class="w-full" muted autoplay loop>
                <source src="{{ asset('images/video.mp4') }}" type="video/mp4">
              </video>
        </div>
        <x-authentication-card>
            <x-slot name="logo">
                <x-authentication-card-logo />
            </x-slot>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Email</label>
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <label class="opacity-60 mb-1 block text-sm font-medium text-italblue">Password</label>
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Olvidaste tu password?') }}
                        </a>
                    @endif

                    <button  type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                        Login
                    </button>
                </div>
            </form>
        </x-authentication-card>
    </div>
</x-guest-layout>
