<x-slot name="header">
    <h2 class="font-semibold text-xl white-text leading-tight">
        {{ __('Categories') }}
    </h2>
</x-slot>

<div class="p-3 flex flex-col gap-3">

    <select wire:model.live="filters.type" class="full border-none rounded-lg secondary-bg light-text">
        <option value="expense">Expenses</option>
        <option value="income">Incomes</option>
    </select>

    <div class=" flex items-center gap-3">
        <input class="w-full border-none rounded-lg secondary-bg light-text" type="date"
            wire:model.live="filters.start_date">
        <input class="w-full border-none rounded-lg secondary-bg light-text" type="date"
            wire:model.live="filters.end_date">
    </div>


    <div @class([
        'text-2xl text-center p-3 secondary-bg rounded-lg font-extrabold',
        'red-text' => $filters['type'] == 'expense',
        'green-text' => $filters['type'] == 'income',
    ])>
        {{ number_format($this->categories->sum('total'), $this->selectedCountry->decimal_points) }}
        <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
    </div>

    {{-- Main Categories --}}
    @foreach ($this->categories->where('category_id', null) as $category)
        @if ($category->grand_total != 0 && $filters)
            <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-clip">
                <div class=" p-3 primary-bg white-text flex items-center justify-between">
                    <div>
                        <div>{{ $category->name }}</div>
                        @if ($this->categories->where('category_id', $category->id)->count() > 0)
                            <a wire:navigate
                                href="{{ route('transaction.index', [
                                    'filters[category_id]' => [$category->id],
                                    'filters[start_date]' => $filters['start_date'],
                                    'filters[end_date]' => $filters['end_date'],
                                ]) }}"
                                @class([
                                    'font-extrabold text-xs block',
                                    'red-text' => $filters['type'] == 'expense',
                                    'green-text' => $filters['type'] == 'income',
                                ])>
                                {{ $category->formatted_total }}
                                <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </a>
                            <a wire:navigate
                                href="{{ route('transaction.index', [
                                    'filters[category_id]' => $this->categories->where('category_id', $category->id)->pluck('id')->toArray(),
                                    'filters[start_date]' => $filters['start_date'],
                                    'filters[end_date]' => $filters['end_date'],
                                ]) }}"
                                @class([
                                    'font-extrabold text-xs block',
                                    'red-text' => $filters['type'] == 'expense',
                                    'green-text' => $filters['type'] == 'income',
                                ])> {{ $category->formatted_sub_categories_total }}
                                <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </a>
                        @endif
                    </div>
                    <div wire:navigate
                        href="{{ route('transaction.index', [
                            'filters[category_id]' => array_merge(
                                [$category->id],
                                $this->categories->where('category_id', $category->id)->pluck('id')->toArray(),
                            ),
                            'filters[start_date]' => $filters['start_date'],
                            'filters[end_date]' => $filters['end_date'],
                        ]) }}"
                        @class([
                            'font-extrabold cursor-pointer',
                            'red-text' => $filters['type'] == 'expense',
                            'green-text' => $filters['type'] == 'income',
                        ])> {{ $category->formatted_grand_total }}
                        <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                    </div>
                </div>

                {{-- Sub Categories --}}
                @if ($this->categories->where('category_id', $category->id)->count() > 0)
                    @foreach ($this->categories->where('category_id', $category->id) as $sub_category)
                        @if ($sub_category->total != 0 && $filters)
                            <div class=" flex items-center justify-between p-3">
                                <div>{{ $sub_category->name }}</div>
                                <a wire:navigate
                                    href="{{ route('transaction.index', [
                                        'filters[category_id]' => [$sub_category->id],
                                        'filters[start_date]' => $filters['start_date'],
                                        'filters[end_date]' => $filters['end_date'],
                                    ]) }}"
                                    @class([
                                        'font-extrabold',
                                        'red-text' => $filters['type'] == 'expense',
                                        'green-text' => $filters['type'] == 'income',
                                    ])> {{ $sub_category->formatted_total }}
                                    <span
                                        class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        @endif
    @endforeach


</div>
