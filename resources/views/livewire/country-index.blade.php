<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Countries') }}
    </h2>
</x-slot>

<div class="p-3 flex flex-col gap-3">

    <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-clip">

        @foreach ($this->countries as $country)
            <div wire:click="setCountry({{ $country->id }})"
                class="flex items-center justify-between cursor-pointer p-3 base-text secondary-bg">
                <div>{{ $country->name }}</div>
                <div @class([
                    'font-extrabold',
                    'red-text' => $country->totalRemaining < 0,
                    'green-text' => $country->totalRemaining > 0,
                ])>
                    {{ $country->formattedTotalRemaining }}
                    <span class=" uppercase text-xs font-thin">{{ $country->currency }}</span>
                </div>
            </div>
        @endforeach

    </div>

</div>
