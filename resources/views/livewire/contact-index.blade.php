<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Contacts') }}
    </h2>
</x-slot>


<div class="p-3 text-gray-900 dark:text-gray-100">
    <div class="text-start bg-white dark:bg-gray-800 text-green-600 dark:text-green-400 text-xs py-3 cursor-pointer"
        wire:click="$emitTo('contact-form','showingModal',null)">
        New Contact
    </div>
    <input tabindex="-1"
        class=" mt-2 w-full border-gray-300 dark:border-indigo-800 dark:bg-gray-800 border-r-0 border-l-0 border-t-0"
        placeholder="Search" type="text">

    <div class="w-full flex flex-col gap-2 mt-2">

        @foreach ($this->contacts as $contact)
            <div
                class="flex items-center justify-between cursor-pointer rounded-lg p-4 shadow dark:shadow-none bg-white text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                <div>{{ $contact->name }}</div>
                @if ($contact->totalRemaining !== 0)
                    <div @class([
                        'font-extrabold',
                        'text-red-600 dark:text-red-400' => $contact->totalRemaining < 0,
                        'text-green-600 dark:text-green-400' => $contact->totalRemaining > 0,
                    ])>
                        {{ number_format(abs($contact->totalRemaining), $this->selectedCountry->decimal_points) }}
                        <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
