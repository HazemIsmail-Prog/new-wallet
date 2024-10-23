<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('New Transaction') }}
    </h2>
</x-slot>

<div x-data="transactionModal()" x-init="getTargetName()" class="p-3 max-w-md mx-auto flex flex-col items-center gap-4">
    <div class="inline-flex w-full shadow-sm gap-1" role="group">
        <template x-for="(value, key) in transaction_types" :key="key">
            <button type="button" @click="setTransaction_type(key)"
                class="
                w-full 
                py-2 
                text-sm 
                rounded-md 
                font-medium 
                border 
                dark:border-none
                rounded-l-lg 
                text-gray-900 
                dark:text-gray-400 
                border-gray-900 
                dark:border-white 
                "
                :class="transaction_type === key ?
                    'ring-gray-500 bg-gray-900 text-white dark:text-gray-900 dark:bg-indigo-700' :
                    ''">
                <span x-text="value.label"></span>
            </button>
        </template>
    </div>

    <div class="w-full flex justify-between items-center gap-3">
        <button wire:ignore type="button" @click="getCurrentRemainingAmount({{ $selectedWallet }})"
            class="w-full px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
            <div>
                {{ $selectedWallet->name }}
            </div>
            <div>
                {{ number_format($selectedWallet->totalRemaining, $this->selectedCountry->decimal_points) }}
            </div>
        </button>

        <!-- Transaction Arrow SVG -->
        <template
            x-if="transaction_type === 'expense' || transaction_type === 'loan_to' || transaction_type === 'transfer' || transaction_type === 'income' || transaction_type === 'loan_from'">
            <svg :class="transaction_type === 'income' || transaction_type === 'loan_from' ? 'rotate-180' : ''"
                class="dark:text-indigo-700 w-12 transition-all duration-150" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </template>


        <button type="button" @click="toggleModals"
            class=" @error('form.target_id') border border-red-600 dark:border-red-400 @enderror w-full inline-flex justify-between items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
            <div class="flex-1" x-text="target_name"></div>
        </button>
    </div>

    <div class="flex flex-col items-center w-full relative">
        <h1 class="mt-6 font-extrabold text-2xl text-gray-800 dark:text-gray-400" x-text="amount">
            <span class="font-light text-sm uppercase">{{ $this->selectedCountry->currency }}</span>
        </h1>
        <div @click="backspace()" class=" absolute right-7 primary-text bottom-0 text-3xl ">
            <x-svgs.back />
        </div>


        <x-input-error :messages="$errors->get('form.amount')" />
    </div>

    <x-calculator />

    <input wire:model="form.notes"
        class="border-none dark:bg-gray-700 dark:text-gray-300 dark:placeholder:text-gray-500 w-full rounded-lg"
        placeholder="notes" type="text">

    <div class="flex w-full gap-4">
        <input wire:model="form.date" class="border-none w-full rounded-lg dark:bg-gray-700 dark:text-gray-300"
            type="date">
        <input wire:model="form.time" class="border-none w-full rounded-lg dark:bg-gray-700 dark:text-gray-300"
            type="time">
    </div>

    <button class="w-full text-white rounded-lg p-4 bg-gray-900 dark:bg-indigo-800 dark:text-gray-300"
        wire:click="save">Save</button>

    {{-- Modals --}}
    <x-my-modal modalName="showWalletsModal" closeAction="resetModals()" title="Select Wallet">
        @foreach ($this->walletsList->where('id', '!=', $this->selectedWallet->id) as $wallet)
<div 
                    @click="handleSelection('App\\Models\\Wallet',{{ $wallet }})"
                class="flex items-center justify-between cursor-pointer p-3 base-text secondary-bg">
                <div>{{ $wallet->name }}</div> @if ($wallet->totalRemaining !== 0)
<div
            @class([
                'font-extrabold text-xs',
                'red-text' => $wallet->totalRemaining < 0,
                'green-text' => $wallet->totalRemaining > 0,
            ])>
            {{ number_format(abs($wallet->totalRemaining), $this->selectedCountry->decimal_points) }} <span
            class="uppercase font-thin">{{ $this->selectedCountry->currency }}</span> </div>
 @endif
            </div>
        @endforeach
        </x-my-modal>

        <x-my-modal modalName="showContactsModal" closeAction="resetModals()" title="Select Contact">
        @foreach ($this->contactsList as $contact)
        <div @click="handleSelection('App\\Models\\Contact',{{ $contact }})"
        class="flex items-center justify-between cursor-pointer p-3 base-text secondary-bg">
        <div>{{ $contact->name }}</div>
        @if ($contact->totalRemaining !== 0)
        <div @class([
            'font-extrabold text-xs',
            'red-text' => $contact->totalRemaining < 0,
            'green-text' => $contact->totalRemaining > 0,
        ])>
        {{ number_format(abs($contact->totalRemaining), $this->selectedCountry->decimal_points) }}
        <span class="uppercase font-thin">{{ $this->selectedCountry->currency }}</span>
        </div>
        @endif
        </div>
        @endforeach
        </x-my-modal>

        <x-my-modal modalName="showIncomesModal" closeAction="resetModals()" title="Select Income">
        <x-slot name="mostUsedItems">
        <div class="flex flex-row-reverse flex-wrap items-center justify-center gap-3">
        @foreach ($this->categoriesList->where('type',
        'income')->sortByDesc('transaction_count')->take(10) as $category)
        <span @click="handleSelection('App\\Models\\Category',{{ $category }})"
        class="secondary-bg cursor-pointer light-text text-sm font-medium px-3 py-1 rounded">{{ $category->name }}</span>
        @endforeach
        </div>
        </x-slot>
        <div class="flex flex-col gap-3 base-bg">
        @foreach ($this->categoriesList->where('type', 'income')->where('category_id',
        null) as $category)
        <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider overflow-clip">
        <div @click="handleSelection('App\\Models\\Category',{{ $category }})"
        class=" p-3 cursor-pointer primary-bg white-text flex items-center justify-between">
        {{ $category->name }}
        </div>
        @if ($this->categoriesList->where('category_id', $category->id)->count() > 0)
        @foreach ($this->categoriesList->where('category_id', $category->id) as $sub_category)
        <div @click="handleSelection('App\\Models\\Category',{{ $sub_category }})"
        class=" cursor-pointer flex items-center justify-between p-3">
        {{ $sub_category->name }}
        </div>
        @endforeach
        @endif
        </div>
        @endforeach
        </div>
        </x-my-modal>

        <x-my-modal modalName="showExpensesModal" closeAction="resetModals()" title="Select Expense">
        <x-slot name="mostUsedItems">
        <div class="flex flex-row-reverse flex-wrap items-center justify-center gap-3">
        @foreach ($this->categoriesList->where('type',
        'expense')->sortByDesc('transaction_count')->take(10) as $category)
        <span @click="handleSelection('App\\Models\\Category',{{ $category }})"
        class="secondary-bg cursor-pointer light-text text-sm font-medium px-3 py-1 rounded">{{ $category->name }}</span>
        @endforeach
        </div>
        </x-slot>
        <div class="flex flex-col gap-3 base-bg">
        @foreach ($this->categoriesList->where('type', 'expense')->where('category_id',
        null) as $category)
        <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider overflow-clip">
        <div @click="handleSelection('App\\Models\\Category',{{ $category }})"
        class=" p-3 cursor-pointer primary-bg white-text flex items-center justify-between">
        {{ $category->name }}
        </div>
        @if ($this->categoriesList->where('category_id', $category->id)->count() > 0)
        @foreach ($this->categoriesList->where('category_id', $category->id) as $sub_category)
        <div @click="handleSelection('App\\Models\\Category',{{ $sub_category }})"
        class=" cursor-pointer flex items-center justify-between p-3">
        {{ $sub_category->name }}
        </div>
        @endforeach
        @endif
        </div>
        @endforeach
        </div>
        </x-my-modal>

        </div>

        <script>
            function getTransactionTypes() {
                return {
                    expense: {
                        label: 'Expense',
                        list: 'categoriesList',
                        modal: 'showExpensesModal'
                    },
                    income: {
                        label: 'Income',
                        list: 'categoriesList',
                        modal: 'showIncomesModal'
                    },
                    transfer: {
                        label: 'Transfer',
                        list: 'walletsList',
                        modal: 'showWalletsModal'
                    },
                    loan_to: {
                        label: 'Loan To',
                        list: 'contactsList',
                        modal: 'showContactsModal'
                    },
                    loan_from: {
                        label: 'Loan From',
                        list: 'contactsList',
                        modal: 'showContactsModal'
                    }
                };
            }

            function transactionModal() {
                return {
                    ...calculator(), // Spread the calculator methods into this object

                    categoriesList: @json($this->categoriesList),
                    walletsList: @json($this->walletsList),
                    contactsList: Array.isArray(@json($this->contactsList)) ? @json($this->contactsList) : Object.values(
                        @json($this->contactsList)),

                    showWalletsModal: false,
                    showContactsModal: false,
                    showIncomesModal: false,
                    showExpensesModal: false,

                    target_name: '---',
                    target_id: @entangle('form.target_id'),
                    target_type: @entangle('form.target_type'),
                    amount: @entangle('form.amount'),

                    transaction_type: @entangle('form.type'),
                    transaction_types: getTransactionTypes(), // Refactored here


                    handleSelection(model, selected) {
                        this.target_id = selected['id'];
                        this.target_name = selected['name'];
                        this.target_type = model;
                        this.resetModals();
                    },

                    getCurrentRemainingAmount(wallet) {
                        this.amount = wallet.totalRemaining
                    },

                    getTargetName() {
                        const listName = this.transaction_types[this.transaction_type]?.list;
                        const list = this[listName];

                        if (list) {
                            const target = list.find(item => item.id === this.target_id);
                            if (target) this.target_name = target.name;
                        }
                    },

                    setTransaction_type(type) {
                        if (this.transaction_type != type) {
                            this.transaction_type = type;
                            this.target_name = '---';
                            this.target_id = null;
                        }
                        this.toggleModals();
                    },

                    toggleModals() {
                        this.resetModals();
                        const modalName = this.transaction_types[this.transaction_type]?.modal;
                        if (modalName) this[modalName] = true;
                    },

                    resetModals() {
                        this.showWalletsModal = false;
                        this.showContactsModal = false;
                        this.showIncomesModal = false;
                        this.showExpensesModal = false;
                    },
                }
            }
        </script>)
