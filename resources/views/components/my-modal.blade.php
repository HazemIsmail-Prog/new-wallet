<div x-cloak x-show="{{ $modalName }}"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80"
    x-transition:leave="opacity-0 duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <!-- Modal Body (Sliding content from below the center) -->
    <div x-show="{{ $modalName }}" x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="flex flex-col gap-3 base-bg base-text p-3 rounded-lg w-[90%] max-h-[70%] max-w-md shadow-lg transform"
        @click.away="{{ $closeAction }}">
        <h3 class="text-lg font-semibold">{{ $title }}</h3>

        {{-- <input class="w-full border-none rounded-lg secondary-bg base-text placeholder:light-text"
            placeholder="Search..." type="text"> --}}

        {{-- Slot for most used items --}}
        <div class="most-used-items">
            {{ $mostUsedItems ?? '' }} {{-- Use the slot variable directly --}}
        </div>


        <div
            class="rounded-lg secondary-bg base-text divide-y-2 gray-divider overflow-clip h-100 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
