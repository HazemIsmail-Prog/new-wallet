<x-slot name="header">
    <h2 class="font-semibold text-xl base-text leading-tight">
        {{ __('Transactions') }}
    </h2>
</x-slot>


<div class="max-w-md mx-auto p-3 flex flex-col gap-3">
    <div x-data="{ expanded: false }" class=" w-full text-end ">
        <button @click="expanded=!expanded"
            class=" p-2 rounded-lg base-text bg-transparent dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
        </button>

        <div class="flex flex-col gap-1">
            <input class="w-full border-none rounded-lg secondary-bg base-text placeholder:light-text" placeholder="Search..." type="text" wire:model.live="filters.search">
            <div class=" flex items-center gap-1">
                <input class="w-full border-none rounded-lg secondary-bg light-text" type="date" wire:model.live="filters.start_date">
                <input class="w-full border-none rounded-lg secondary-bg light-text" type="date" wire:model.live="filters.end_date">
            </div>
        </div>

        {{-- <div x-show="expanded" class=" rounded-lg mt-2 border dark:border-none">
            <div class=" flex flex-col gap-2">
                <select wire:model="filter.selected_wallet"
                    class=" text-center border  text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <option value="">Select Wallet</option>
                    @foreach ($filter['wallet_list'] as $row)
                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                    @endforeach
                </select>
                <select wire:model="filter.selected_expense"
                    class=" text-center border  text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <option value="">Select Expenses</option>
                    @foreach ($filter['expense_list'] as $row)
                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                    @endforeach
                </select>
                <select wire:model="filter.selected_income"
                    class=" text-center border  text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <option value="">Select Income</option>
                    @foreach ($filter['income_list'] as $row)
                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                    @endforeach
                </select>
                <select wire:model="filter.selected_contact"
                    class=" text-center border  text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <option value="">Select Contact</option>
                    @foreach ($filter['contact_list'] as $row)
                        <option value="{{ $row['id'] }}">{{ $row['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}

    </div>

    @foreach ($this->transactions as $date => $transactions)
        <div
            class=" border dark:border-none rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-clip">
            <div
                class=" p-3 primary-bg white-text flex items-center justify-between">
                <div>
                    <div>{{ $transactions->first()->date->format('D') }}</div>
                    <div>{{ $date }}</div>
                </div>
                <div class=" text-right">

                    @if ($transactions->first()->date->isToday())
                        <div>Today</div>
                    @endif
                    @if ($transactions->first()->date->isYesterday())
                        <div>Yesterday</div>
                    @endif
                    <div class=" green-text font-extrabold">
                        {{ number_format($transactions->whereIn('type', ['income', 'loan_from'])->sum('amount'), $this->selectedCountry->decimal_points) }}
                        <span class=" uppercase font-extralight text-xs">{{ $this->selectedCountry->currency }}</span>
                    </div>
                    <div class=" red-text font-extrabold">
                        {{ number_format($transactions->whereIn('type', ['expense', 'loan_to'])->sum('amount'), $this->selectedCountry->decimal_points) }}
                        <span class=" uppercase font-extralight text-xs">{{ $this->selectedCountry->currency }}</span>
                    </div>
                </div>
            </div>
            @foreach ($transactions->sortByDesc('id')->sortByDesc('time') as $transaction)
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
                                        <span class="text-xs uppercase">{{ $this->selectedCountry->currency }}</span>
                                    </div>
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
                                        <span class="text-xs uppercase">{{ $this->selectedCountry->currency }}</span>
                                    </div>
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
                                        <span class="text-xs uppercase">{{ $this->selectedCountry->currency }}</span>
                                    </div>
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
                                        <span class="text-xs uppercase">{{ $this->selectedCountry->currency }}</span>
                                    </div>
                                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                                </div>
                            @break

                            @case('transfer')
                                <div class="flex flex-col flex-1">
                                    <span class="light-text">Transfer</span>
                                    <div class=" flex items-center gap-1 text-xs font-extrabold">
                                        {{ $transaction->wallet->name }}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                        </svg>
                                        {{ $transaction->target->name }}
                                    </div>
                                    <div class=" font-extralight light-text text-xs">{{ $transaction->notes }}</div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="font-bold">
                                        {{ number_format($transaction->amount, $this->selectedCountry->decimal_points) }}
                                        <span class="text-xs uppercase">{{ $this->selectedCountry->currency }}</span>
                                    </div>
                                    <div class=" text-xs light-text">{{ $transaction->time->format('H:i') }}</div>
                                </div>
                            @break
                        @endswitch
                    </a>
                    <svg onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                        wire:click="delete({{ $transaction }})" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-5 h-5 red-text mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>

                </div>
            @endforeach
        </div>
    @endforeach
    <div>{{ $this->transactions->links(data: ['scrollTo' => false]) }}</div>

</div>
