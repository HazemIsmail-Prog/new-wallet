<x-slot name="header">
    <h2 class="font-semibold text-xl base-text leading-tight">
        {{ __('Transactions') }}
    </h2>
</x-slot>

<div class="p-3 flex flex-col gap-3">

    <x-text-input placeholder="Search..." type="text" wire:model.live="filters.search" />

    <div class=" flex items-center gap-3">
        <x-text-input type="date" wire:model.live="filters.start_date" />
        <x-text-input type="date" wire:model.live="filters.end_date" />
    </div>

    <div class="text-end font-extrabold">
        <div class="green-text">
            {{ $this->summary->formattedTotalIncomes }}
            <x-active-currency />
        </div>
        <div class="red-text">
            {{ $this->summary->formattedTotalExpenses }}
            <x-active-currency />
        </div>
    </div>

    @foreach ($this->transactions as $date => $transactions)
        <x-transaction-day-group 
            :transactions="$transactions" 
            :date="$transactions->first()->date" 
            :formattedTotalIncomes="$transactions->formattedTotalIncomes" 
            :formattedTotalExpenses="$transactions->formattedTotalExpenses" 
        />
    @endforeach

    <div>{{ $this->transactions->links(data: ['scrollTo' => false]) }}</div>
</div>
