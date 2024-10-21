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
    <input tabindex="-1"
        class=" mt-2 w-full border-gray-300 dark:border-indigo-800 dark:bg-gray-800 border-r-0 border-l-0 border-t-0"
        placeholder="Search" type="text">

    <div class="w-full flex flex-col gap-2 mt-2">

        @foreach ($this->categories->where('category_id', null)->sortByDesc('totalRemaining')->sortBy('type') as $category)
            <div
                class="flex flex-col cursor-pointer rounded-lg shadow dark:shadow-none overflow-clip text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                <div class=" flex items-center justify-between bg-white p-4">
                    <div>
                        <div>{{ $category->name }}</div>
                        <div @class([
                            'font-extrabold text-xs',
                            'text-red-600 dark:text-red-400' => $category->type == 'expense',
                            'text-green-600 dark:text-green-400' => $category->type == 'income',
                        ])>
                            {{ number_format(abs($category->totalRemaining), $this->selectedCountry->decimal_points) }}
                            <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                        </div>
                    </div>
                    <div @class([
                        'font-extrabold',
                        'text-red-600 dark:text-red-400' => $category->type == 'expense',
                        'text-green-600 dark:text-green-400' => $category->type == 'income',
                    ])>
                        {{ number_format(abs($category->totalRemaining + $this->categories->where('category_id',$category->id)->sum('totalRemaining')), $this->selectedCountry->decimal_points) }}
                        <span class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                    </div>
                </div>
                @if ($this->categories->where('category_id', $category->id)->count() > 0)
                    <div class="p-4 divide-y">
                        @foreach ($this->categories->where('category_id', $category->id)->sortByDesc('totalRemaining')->sortBy('type') as $sub_category)
                            <div class=" flex items-center justify-between py-1">
                                <div>{{ $sub_category->name }}</div>
                                @if ($sub_category->totalRemaining !== 0)
                                    <div @class([
                                        'font-extrabold',
                                        'text-red-600 dark:text-red-400' => $sub_category->type == 'expense',
                                        'text-green-600 dark:text-green-400' => $sub_category->type == 'income',
                                    ])>
                                        {{ number_format(abs($sub_category->totalRemaining), $this->selectedCountry->decimal_points) }}
                                        <span
                                            class=" uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
