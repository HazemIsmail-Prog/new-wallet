<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Categories') }}
    </h2>
</x-slot>

<div class="p-6 text-gray-900 dark:text-gray-100">
    <div class="text-start bg-white dark:bg-gray-800 text-green-600 dark:text-green-400 text-xs py-3 cursor-pointer"
        wire:click="$emitTo('category-form','showingModal',null)">
        New Category
    </div>

    <select wire:model.live="filters.type">
        <option value="expense">Expenses</option>
        <option value="income">Incomes</option>
    </select>

    <div class=" flex items-center gap-1">
        <input class="w-full border-none rounded-lg" type="date" wire:model.live="filters.start_date">
        <input class="w-full border-none rounded-lg" type="date" wire:model.live="filters.end_date">
    </div>

    <div class="w-full flex flex-col gap-2 mt-2">

        <div @class([
            'flex items-center justify-center gap-1 text-2xl p-3 bg-white rounded-lg font-extrabold',
            'text-red-600 dark:text-red-400' => $filters['type'] == 'expense',
            'text-green-600 dark:text-green-400' => $filters['type'] == 'income',
        ])>
            <div>{{ number_format($this->categories->sum('total'), $this->selectedCountry->decimal_points) }}</div>
            <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>

        </div>

        @foreach ($this->categories->where('category_id', null) as $category)
            @if ($category->grand_total != 0 && $filters)
                <div
                    class="flex flex-col cursor-pointer rounded-lg shadow dark:shadow-none overflow-clip text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                    <div class=" flex items-center justify-between bg-white p-4">
                        <div>
                            <div>{{ $category->name }}</div>
                            <div @class([
                                'font-extrabold text-xs',
                                'text-red-600 dark:text-red-400' => $filters['type'] == 'expense',
                                'text-green-600 dark:text-green-400' => $filters['type'] == 'income',
                            ])>
                                {{ $category->formatted_total }}
                                <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                            <div @class([
                                'font-extrabold text-xs',
                                'text-red-600 dark:text-red-400' => $filters['type'] == 'expense',
                                'text-green-600 dark:text-green-400' => $filters['type'] == 'income',
                            ])> {{ $category->formatted_sub_categories_total }}
                                <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        </div>
                        <div @class([
                            'font-extrabold',
                            'text-red-600 dark:text-red-400' => $filters['type'] == 'expense',
                            'text-green-600 dark:text-green-400' => $filters['type'] == 'income',
                        ])> {{ $category->formatted_grand_total }}
                            <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                        </div>
                    </div>
                    @if ($this->categories->where('category_id', $category->id)->count() > 0)
                        <div class="p-4 divide-y">
                            @foreach ($this->categories->where('category_id', $category->id) as $sub_category)
                                @if ($sub_category->total != 0 && $filters)
                                    <div class=" flex items-center justify-between py-1">
                                        <div>{{ $sub_category->name }}</div>
                                        <div @class([
                                            'font-extrabold',
                                            'text-red-600 dark:text-red-400' => $filters['type'] == 'expense',
                                            'text-green-600 dark:text-green-400' => $filters['type'] == 'income',
                                        ])> {{ $sub_category->formatted_total }}
                                            <span
                                                class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
