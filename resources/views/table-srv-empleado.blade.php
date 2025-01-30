<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden mt-14">
            @livewire('table-srv-empleado',
            [
                'empleado_id' => Auth::user()->id,
            ])

        </div>
        <div class="overflow-hidden mt-14">
            @livewire('table-prod-empleado',
            [
                'empleado_id' => Auth::user()->id,
            ])

        </div>

    </div>
</x-app-layout>
