<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('New Transaction') }}
    </h2>
</x-slot>


<div x-data="transactionModal()" x-init="getTargetName()"
    class="p-6  max-w-md mx-auto flex flex-col items-center gap-4">

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
                <span x-text="value"></span>
            </button>
        </template>
    </div>

    <div class="w-full flex justify-between items-center gap-3">
        <button wire:ignore type="button"
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
            x-if="transaction_type === 'expense' || transaction_type === 'loan_to' || transaction_type === 'transfer'">
            <svg class="dark:text-indigo-700 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </template>

        <template x-if="transaction_type === 'income' || transaction_type === 'loan_from'">
            <svg class="dark:text-indigo-700 w-12 rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </template>

        <button type="button" @click="toggleModals"
            class=" @error('form.target_id') border border-red-600 dark:border-red-400 @enderror w-full inline-flex justify-between items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
            <div class="flex-1" x-text="target_name"></div>
        </button>
    </div>

    <div class="flex flex-col items-center">
        <h1 class="mt-6 font-extrabold text-2xl text-gray-800 dark:text-gray-400" x-text="amount">
            <span class="font-light text-sm uppercase">{{ $this->selectedCountry->currency }}</span>
        </h1>

        <x-input-error :messages="$errors->get('form.amount')" />
    </div>

    {{-- <x-calculator /> --}}
    <div class="w-full">
        <div class="w-full h-52 flex">
            <div
                class=" flex-1 grid grid-cols-4 grid-rows-4 bg-white dark:text-gray-400 dark:bg-gray-700 rounded-xl overflow-clip dark:border-gray-900 divide-x divide-y dark:divide-gray-900 border">
                <input class="border-t border-l dark:border-gray-900" type="button" value="7" @click="dis('7')">
                <input type="button" value="8" @click="dis('8')">
                <input type="button" value="9" @click="dis('9')">
                <div
                    class="flex flex-col flex-3 row-span-3 items-center justify-evenly bg-gray-900 text-white dark:bg-indigo-900 dark:text-gray-400 divide-y dark:divide-gray-900">
                    <input class="w-full text-2xl" type="button" value="÷" @click="dis('/')">
                    <input class="w-full text-2xl" type="button" value="×" @click="dis('*')">
                    <input class="w-full text-2xl" type="button" value="-" @click="dis('-')">
                    <input class="w-full text-2xl" type="button" value="+" @click="dis('+')">
                </div>
                <input type="button" value="4" @click="dis('4')">
                <input type="button" value="5" @click="dis('5')">
                <input type="button" value="6" @click="dis('6')">
                <input type="button" value="1" @click="dis('1')">
                <input type="button" value="2" @click="dis('2')">
                <input type="button" value="3" @click="dis('3')">
                <input type="button" value="." @click="dis('.')">
                <input type="button" value="0" @click="dis('0')">
                <input class=" text-2xl bg-gray-900 text-white dark:bg-indigo-900 dark:text-gray-400" type="button"
                    value="c" @click="clr()" />
                <input class="w-full h-full text-2xl bg-gray-900 text-white dark:bg-indigo-900 dark:text-gray-400"
                    type="button" value="=" @click="solve()">

            </div>
        </div>

    </div>

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
    <div x-cloak x-show="showWalletsModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-900 text-white  p-4 w-[80%] max-w-md rounded-lg shadow-lg"
            @click.away="showWalletsModal = false">
            <h3 class="text-lg font-semibold">Select Wallet</h3>
            <div class="mt-4">
                @foreach ($this->walletsList->where('id','!=',$this->selectedWallet->id) as $wallet)
                    <div class="w-full cursor-pointer p-3 bg-white dark:bg-gray-900"
                        @click="handleSelection('App\\Models\\Wallet',{{ $wallet }})">
                        <div>{{ $wallet->name }}</div>
                        @if ($wallet->totalRemaining < 0)
                            <div class="font-extrabold text-red-600 dark:text-red-400">
                                {{ number_format(abs($wallet->totalRemaining), $this->selectedCountry->decimal_points) }}
                                <span class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                        @if ($wallet->totalRemaining > 0)
                            <div class="font-extrabold text-green-600 dark:text-green-400">
                                {{ number_format($wallet->totalRemaining, $this->selectedCountry->decimal_points) }}
                                <span class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div x-cloak x-show="showContactsModal"
        class="fixed inset-0 z-50  flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-900 text-white p-4 rounded-lg w-[80%] max-w-md shadow-lg"
            @click.away="showContactsModal = false">
            <h3 class="text-lg font-semibold">Select Contact</h3>
            <div class="mt-4 h-96 overflow-y-auto">
                @foreach ($this->contactsList as $contact)
                    <div class="w-full cursor-pointer p-3 bg-white dark:bg-gray-900"
                        @click="handleSelection('App\\Models\\Contact',{{ $contact }})">
                        <div>{{ $contact->name }}</div>
                        @if ($contact->available_amount < 0)
                            <div class="font-extrabold text-red-600 dark:text-red-400">
                                {{ number_format(abs($contact->available_amount), $this->selectedCountry->decimal_points) }}
                                <span
                                    class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                        @if ($contact->available_amount > 0)
                            <div class="font-extrabold text-green-600 dark:text-green-400">
                                {{ number_format($contact->available_amount, $this->selectedCountry->decimal_points) }}
                                <span
                                    class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div x-cloak x-show="showIncomesModal"
        class="fixed inset-0 z-50  flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-900 text-white p-4 rounded-lg w-[80%] max-w-md shadow-lg"
            @click.away="showIncomesModal = false">
            <h3 class="text-lg font-semibold">Select Income</h3>
            <div class="mt-4 h-96 overflow-y-auto">
                @foreach ($this->incomesList as $income)
                    <div class="w-full cursor-pointer p-3 bg-white dark:bg-gray-900"
                        @click="handleSelection('App\\Models\\Category',{{ $income }})">
                        <div>{{ $income->name }}</div>
                        @if ($income->available_amount < 0)
                            <div class="font-extrabold text-red-600 dark:text-red-400">
                                {{ number_format(abs($income->available_amount), $this->selectedCountry->decimal_points) }}
                                <span
                                    class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                        @if ($income->available_amount > 0)
                            <div class="font-extrabold text-green-600 dark:text-green-400">
                                {{ number_format($income->available_amount, $this->selectedCountry->decimal_points) }}
                                <span
                                    class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div x-cloak x-show="showExpensesModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-900 dark:text-white p-4 rounded-lg w-[80%] max-w-md shadow-lg"
            @click.away="showExpensesModal = false">
            <h3 class="text-lg font-semibold">Select expense</h3>
            <div class="mt-4 h-96 overflow-y-auto">
                @foreach ($this->expensesList as $expense)
                    <div class="w-full cursor-pointer p-3 bg-white dark:bg-gray-900"
                        @click="handleSelection('App\\Models\\Category',{{ $expense }})">
                        <div>{{ $expense->name }}</div>
                        @if ($expense->available_amount < 0)
                            <div class="font-extrabold text-red-600 dark:text-red-400">
                                {{ number_format(abs($expense->available_amount), $this->selectedCountry->decimal_points) }}
                                <span
                                    class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                        @if ($expense->available_amount > 0)
                            <div class="font-extrabold text-green-600 dark:text-green-400">
                                {{ number_format($expense->available_amount, $this->selectedCountry->decimal_points) }}
                                <span
                                    class="uppercase text-xs font-thin">{{ $this->selectedCountry->currency }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.6.4/math.js"
    integrity="sha512-BbVEDjbqdN3Eow8+empLMrJlxXRj5nEitiCAK5A1pUr66+jLVejo3PmjIaucRnjlB0P9R3rBUs3g5jXc8ti+fQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.6.4/math.min.js"
    integrity="sha512-iphNRh6dPbeuPGIrQbCdbBF/qcqadKWLa35YPVfMZMHBSI6PLJh1om2xCTWhpVpmUyb4IvVS9iYnnYMkleVXLA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function transactionModal() {
        return {

            expensesList: @json($this->expensesList),
            incomesList: @json($this->incomesList),
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
            transaction_types: {
                expense: 'Expense',
                income: 'Income',
                transfer: 'Transfer',
                loan_to: 'Loan To',
                loan_from: 'Loan From',
            }, // Define transaction types

            handleSelection(model, selected) {
                this.target_id = selected['id'];
                this.target_name = selected['name'];
                this.target_type = model;
                this.showWalletsModal = false
                this.showContactsModal = false
                this.showExpensesModal = false
                this.showIncomesModal = false
            },

            getTargetName() {
                switch (this.transaction_type) {
                    case 'expense':
                        const expense = this.expensesList.find(item => item.id === this.target_id);
                        if (expense) this.target_name = expense.name;
                        break;
                    case 'transfer':
                        const wallet = this.walletsList.find(item => item.id === this.target_id);
                        if (wallet) this.target_name = wallet.name;
                        break;
                    case 'income':
                        const income = this.incomesList.find(item => item.id === this.target_id);
                        if (income) this.target_name = income.name;
                        break;
                    case 'loan_from':
                        const contact_from = this.contactsList.find(item => item.id === this.target_id);
                        if (contact_from) this.target_name = contact_from.name;
                        break;
                    case 'loan_to':
                        const contact_to = this.contactsList.find(item => item.id === this.target_id);
                        if (contact_to) this.target_name = contact_to.name;
                        break;
                }
            },

            setTransaction_type(type) {
                this.transaction_type = type;
                this.target_name = '---';
                this.target_id = null;
            },

            toggleModals() {
                this.showWalletsModal = this.transaction_type === 'transfer';
                this.showContactsModal = this.transaction_type === 'loan_to' || this.transaction_type === 'loan_from';
                this.showIncomesModal = this.transaction_type === 'income';
                this.showExpensesModal = this.transaction_type === 'expense';
            },

            // Function that display value
            dis(val) {
                if (this.amount == 0) {
                    this.amount = '';
                }
                this.amount += val
            },

            // Function that evaluates the digit and return result
            solve() {
                let x = this.amount
                let y = math.evaluate(x)
                this.amount = y
            },

            // Function that clear the display
            clr() {
                this.amount = 0
            },
        }
    }
</script>
