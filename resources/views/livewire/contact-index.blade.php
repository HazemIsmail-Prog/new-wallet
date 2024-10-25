<x-slot name="header">
    <h2 class="font-semibold text-xl base-text leading-tight">
        {{ __('Contacts') }}
    </h2>
</x-slot>

<div class="p-3 flex flex-col gap-3">

    <x-text-input placeholder="Search..." type="text" wire:model.live="filters.search" />

    <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-clip">

        @foreach ($this->contacts as $contact)
            <div class="flex items-center justify-between cursor-pointer p-3 base-text secondary-bg">
                <div>{{ $contact->name }}</div>
                @if ($contact->totalRemaining !== 0)
                    <a wire:navigate href="{{ route('transaction.index', ['filters[contact_id]' => $contact->id]) }}"
                        @class([
                            'font-extrabold',
                            'red-text' => $contact->totalRemaining < 0,
                            'green-text' => $contact->totalRemaining > 0,
                        ])>
                        {{ $contact->formattedTotalRemaining }}
                        <x-active-currency /> </a>
                @endif
            </div>
        @endforeach

    </div>

</div>
