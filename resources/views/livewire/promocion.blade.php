<div>
    <div class="p-5">
        <ul class="grid w-full gap-6 md:grid-cols-3">
            @foreach ($data as $item)
            <li>
                <input type="checkbox" id="react-option" value="" class="hidden peer" required="">
                <label for="react-option" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block" onclick="Livewire.dispatch('openModal', { component: 'modal-promociones', arguments: { promocion: {{ $item->id }} }})">
                        <img src="{{ asset('storage/'.$item->image) }}"></img>
                        <div class="w-full text-lg font-semibold">{{ $item->descripcion }}</div>
                        <div class="w-full text-sm">Modalidad: {{ $item->tipo }}</div>
                    </div>
                </label>
            </li>
            @endforeach
        </ul>
        <div class="w-full h-28"></div>
    </div>
    <x-menu_table />
</div>


