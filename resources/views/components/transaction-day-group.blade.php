@props(['transactions', 'date', 'formattedTotalIncomes', 'formattedTotalExpenses'])

@php
    $isTodayOrYesterday = match (true) {
        $date->isToday() => 'Today',
        $date->isYesterday() => 'Yesterday',
        default => false,
    };
@endphp

<div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-clip">
    <div class=" p-3 primary-bg white-text flex items-center justify-between">
        <div>
            <div>{{ $date->format('D') }}</div>
            <div>{{ $date->format('d-m-Y') }}</div>
        </div>
        <div class=" text-right">
            {{ $isTodayOrYesterday }}
            <div class=" green-text font-extrabold">
                {{ $formattedTotalIncomes }}
                <x-active-currency />
            </div>
            <div class=" red-text font-extrabold">
                {{ $formattedTotalExpenses }}
                <x-active-currency />
            </div>
        </div>
    </div>
    @foreach ($transactions->sortByDesc('id')->sortByDesc('time') as $transaction)
        <x-transaction-row :transaction="$transaction" />
    @endforeach
</div>
