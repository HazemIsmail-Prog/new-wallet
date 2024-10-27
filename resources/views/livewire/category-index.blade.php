<x-slot name="header">
    <h2 class="flex items-start justify-between font-semibold text-xl base-text leading-tight">
        {{ __('Categories') }}
        <div id="new"></div>
    </h2>
</x-slot>

<div x-data="form()" class="p-3 flex flex-col gap-3">

    <template x-teleport="#new">
        <button x-on:click="showModal()">Add</button>
    </template>

    <select wire:model.live="filters.type"
        class="w-full secondary-bg border-none light-text focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md">
        <option value="expense">Expenses</option>
        <option value="income">Incomes</option>
    </select>

    <div class="flex items-center gap-3">
        <x-text-input type="date" wire:model.live="filters.start_date" />
        <x-text-input type="date" wire:model.live="filters.end_date" />
    </div>

    <div @class([
        'text-2xl text-center p-3 secondary-bg rounded-lg font-extrabold',
        'red-text' => $filters['type'] == 'expense',
        'green-text' => $filters['type'] == 'income',
    ])>
        {{ number_format($this->categories->sum('total'), session('activeCountry')->decimal_points) }}
        <x-active-currency />
    </div>

    {{-- Main Categories --}}
    @foreach ($this->parentCategoriesList as $category)
        <div
            class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-clip overflow-y-visible">
            <div class="p-3 primary-bg white-text flex items-center justify-between">
                <div>
                    <div>
                        {{ $category->name }}
                    </div>
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
                            <x-active-currency />
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
                            ])>
                            {{ $category->formatted_sub_categories_total }}
                            <x-active-currency />
                        </a>
                    @endif
                </div>
                <div class=" flex items-center gap-2">
                    <div @class([
                        'font-extrabold cursor-pointer',
                        'red-text' => $filters['type'] == 'expense',
                        'green-text' => $filters['type'] == 'income',
                    ])>
                        {{ $category->formatted_grand_total }}
                        <x-active-currency />
                    </div>
                    <button x-data="{ show: false }" class=" relative" x-on:click.outside="show = false"
                        x-on:click="show = !show">
                        <x-svgs.vertical-dots />
                        <div x-cloak x-show="show"
                            class=" base-text divide-y absolute top-0 right-8 border shadow-md z-10 overflow-hidden bg-white rounded-lg">
                            <a wire:navigate class=" block p-3 w-full cursor-pointer"
                                href="{{ route('transaction.index', [
                                    'filters[category_id]' => array_merge(
                                        [$category->id],
                                        $this->categories->where('category_id', $category->id)->pluck('id')->toArray(),
                                    ),
                                    'filters[start_date]' => $filters['start_date'],
                                    'filters[end_date]' => $filters['end_date'],
                                ]) }}">View
                                Transactions
                            </a>
                            <input wire:key="{{ $category->id }}" x-on:click="showModal( null, {{ $category }})"
                                class="p-3 w-full cursor-pointer" type="button" value="Add Sub-Category">
                            <input wire:key="{{ $category->id }}" x-on:click="showModal({{ $category }}, null )"
                                class="p-3 w-full cursor-pointer" type="button" value="Edit">
                            <input wire:confirm="Are you sure?!" wire:click="delete({{ $category }})"
                                class="p-3 w-full cursor-pointer" type="button" value="Delete">
                        </div>
                    </button>
                </div>
            </div>

            {{-- Sub Categories --}}
            @foreach ($this->subCategoriesList($category->id) as $sub_category)
                <div wire:key="{{ $sub_category->id }}" class="flex items-center justify-between p-3">
                    <div>
                        {{ $sub_category->name }}
                    </div>
                    <div class="flex items-center gap-2">
                        <div @class([
                            'font-extrabold',
                            'red-text' => $filters['type'] == 'expense',
                            'green-text' => $filters['type'] == 'income',
                        ])>
                            {{ $sub_category->formatted_total }}
                            <x-active-currency />
                        </div>
                        <button x-data="{ show: false }" class=" relative" x-on:click.outside="show = false"
                            x-on:click="show = !show">
                            <x-svgs.vertical-dots />
                            <div x-cloak x-show="show"
                                class=" base-text divide-y absolute top-0 right-8 border shadow-md z-10 overflow-hidden bg-white rounded-lg">
                                <a wire:navigate class="block p-3 w-full  whitespace-nowrap cursor-pointer"
                                    href="{{ route('transaction.index', [
                                        'filters[category_id]' => [$sub_category->id],
                                        'filters[start_date]' => $filters['start_date'],
                                        'filters[end_date]' => $filters['end_date'],
                                    ]) }}">
                                    View Transactions
                                </a>
                                <input x-on:click="showModal( {{ $sub_category }}, {{ $category }} )"
                                    class="p-3 w-full cursor-pointer" type="button" value="Edit">
                                <input wire:confirm="Are you sure?!" wire:click="delete({{ $sub_category }})"
                                    class="p-3 w-full cursor-pointer" type="button" value="Delete">
                            </div>
                        </button>
                    </div>
                </div>
            @endforeach


        </div>
    @endforeach

    <x-my-modal modalName="formModal" closeAction="closeModal()">
        <form x-on:submit.prevent="save">
            <template x-if="showParentSelector">
                <select wire:key="selectParentList" x-model="form.category_id">
                    <option value="">---</option>
                    @foreach ($this->parentCategoriesList->where('type', $this->filters['type']) as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

            </template>
            <x-text-input x-ref="nameInput" required x-model="form.name" placeholder="Name" />
            <button>Save</button>
        </form>
    </x-my-modal>

</div>

<script>
    function form() {
        return {
            categoriesList: @json($this->categories),
            formModal: false,
            showParentSelector: false,
            modalTitle: '',
            form: @entangle('form'),

            init() {
                Livewire.on('modalClosed', () => this.closeModal());
            },

            toggleParentSelector(categoryId = null) {
                if (categoryId)
                    this.showParentSelector = !Object.values(this.categoriesList).some(category => category
                        .category_id === categoryId);
            },

            showModal(category = null, parent = null) {
                if (category) {
                    this.toggleParentSelector(category.id);
                    this.modalTitle = `Edit ${category.name}`;
                    this.form.id = category.id;
                    this.form.name = category.name;
                    this.form.category_id = category.category_id;
                } else {
                    this.modalTitle = parent ? `New Sub-Category for ${parent.name}` : 'New Category';
                    this.form.category_id = parent ? parent.id : null;
                }
                this.formModal = true;
                this.$nextTick(() => this.$refs.nameInput.focus());
            },

            closeModal() {
                this.formModal = false;
                this.resetForm();
            },

            resetForm() {
                this.showParentSelector = false;
                this.form.id = null;
                this.form.name = '';
                this.form.category_id = null;
            },

            save() {
                @this.save();
            },
        };
    }
</script>
