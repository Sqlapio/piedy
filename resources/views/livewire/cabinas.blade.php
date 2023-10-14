<div>
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-4 mt-8">
        <div class="p-2">
            <div class="flex align-items-center">
                <img class="rounded-full h-12 w-12  object-cover" src="https://images.unsplash.com/photo-1613588718956-c2e80305bf61?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=634&q=80" alt="unsplash image">
                <div class="ml-3">
                    <div class="">Appple</div>
                    <div class="text-gray-500">mail@rgmail.com</div>
                </div>
            </div>
        </div>
        <div class="p-2">
            <div class="flex align-items-center">
                <img class="rounded-full h-12 w-12  object-cover" src="https://images.unsplash.com/photo-1613588718956-c2e80305bf61?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=634&q=80" alt="unsplash image">
                <div class="ml-3">
                    <div class="">Appple</div>
                    <div class="text-gray-500">mail@rgmail.com</div>
                </div>
            </div>
        </div>
        <div class="p-2">
            200.00$
        </div>
        <div class="p-2">
            200.00$
        </div>
        <div class="p-2">
            200.00$
        </div>
    </div>
    <div style="text-align: center">
        <button wire:poll.1000ms="increment">+</button>
        <h1>60:{{ $count }}</h1>
    </div>
</div>
