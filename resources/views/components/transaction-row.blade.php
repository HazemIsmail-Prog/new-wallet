@props(['transaction'])

<div class="flex items-center ">
    <a wire:navigate href="{{ route('transaction.form', $transaction) }}"
        class=" flex-1 flex justify-between items-center p-3">
        @switch($transaction->type)
            @case('expense')
                <div class="flex flex-col flex-1">
                    <div class=" font-extrabold">
                        {{ $transaction->target->category_id
                            ? $transaction->target->parent_category->name . ' - ' . $transaction->target->name
                            : $transaction->target->name }}
                    </div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->wallet->name }}</div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->notes }}</div>
                </div>
                <div class="flex flex-col items-end">
                    <div class=" red-text font-bold">
                        {{ number_format($transaction->amount, $this->selectedCountry->decimal_points) }}
                        <x-active-currency/>                    </div>
                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                </div>
            @break

            @case('loan_to')
                <div class="flex flex-col  flex-1">
                    <div class=" font-extrabold">{{ $transaction->target->name }}</div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->wallet->name }}</div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->notes }}</div>
                </div>
                <div class="flex flex-col items-end">
                    <div class=" red-text font-bold">
                        {{ number_format($transaction->amount, $this->selectedCountry->decimal_points) }}
                        <x-active-currency/>                    </div>
                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                </div>
            @break

            @case('income')
                <div class="flex flex-col flex-1">
                    <div class=" font-extrabold">
                        {{ $transaction->target->category_id
                            ? $transaction->target->parent_category->name . ' - ' . $transaction->target->name
                            : @$transaction->target->name }}
                    </div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->wallet->name }}</div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->notes }}</div>
                </div>
                <div class="flex flex-col items-end">
                    <div class=" green-text font-bold">
                        {{ number_format($transaction->amount, $this->selectedCountry->decimal_points) }}
                        <x-active-currency/>                    </div>
                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                </div>
            @break

            @case('loan_from')
                <div class="flex flex-col flex-1">
                    <div class=" font-extrabold">{{ $transaction->target->name }}</div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->wallet->name }}</div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->notes }}</div>
                </div>
                <div class="flex flex-col items-end">
                    <div class=" green-text font-bold">
                        {{ number_format($transaction->amount, $this->selectedCountry->decimal_points) }}
                        <x-active-currency/>                    </div>
                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                </div>
            @break

            @case('transfer')
                <div class="flex flex-col flex-1">
                    <span class="light-text">Transfer</span>
                    <div class=" flex items-center gap-1 text-xs font-extrabold">
                        {{ $transaction->wallet->name }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                        {{ $transaction->target->name }}
                    </div>
                    <div class=" font-extralight light-text text-xs">{{ $transaction->notes }}</div>
                </div>
                <div class="flex flex-col items-end">
                    <div class="font-bold">
                        {{ number_format($transaction->amount, $this->selectedCountry->decimal_points) }}
                        <x-active-currency/>                    </div>
                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                </div>
            @break
        @endswitch
    </a> <x-svgs.trash wire:confirm="Are you sure?" wire:click="delete({{ $transaction->id }})"
        class="!w-5 !h-5 red-text mr-3" />
</div>
