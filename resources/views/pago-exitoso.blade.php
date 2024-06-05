<x-guest-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden mt-14">
            <div class="bg-gray-100">
                <div class="flex flex-col min-[250px]:justify-center min-[250px]:p-4 items-center pt-4 sm:pt-0 bg-gray-100">
                    <div>
                        <x-authentication-card-logo />
                    </div>
                    <div class="w-1/2">
                        <img src="{{ asset('images/pago_exitoso.gif') }}" alt="">
                    </div>
                    <div class="w-full sm:max-w-md -mt-10 px-6 py-4overflow-hidden sm:rounded-lg">
                        <h3 class="md:text-2xl text-base text-green-700 font-extrabold text-center">Pago Exitoso!</h3>
                      <p class="text-gray-600 text-center my-2">Gracias por su visita!</p>
                      {{-- <p class="text-gray-600 text-center my-2"> Have a great day!  </p> --}}
                      <div class="flex items-center justify-center mt-6">
                        <a href="{{ route('login-externo') }}" type="submit" class="justify-end rounded-md border bg-[#7798a4] py-2 px-4 text-sm text-white font-bold shadow-sm hover:bg-green">
                            <svg xmlns="http://www.w3.org/2000/svg" wire:loading wire:target="validar_usuario" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin h-5 w-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            FINALIZAR PROCESO
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
