<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('New Transaction') }}
    </h2>
</x-slot>

<div x-data="transactionModal()" x-init="getTargetName()" class="p-3 flex flex-col h-full justify-end items-center gap-3">

    {{-- transaction types row --}}
    <div class="w-full flex shadow-sm gap-1" role="group">
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

    {{-- wallet and target selectors row --}}
    <div class="w-full flex justify-between items-center gap-3">
        <button wire:ignore type="button" @click="getCurrentRemainingAmount({{ $selectedWallet }})"
            class="w-full px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
            <div>
                {{ $selectedWallet->name }}
            </div>
            <div @class([
                'font-extrabold text-xs',
                'red-text' => $selectedWallet->totalRemaining < 0,
                'green-text' => $selectedWallet->totalRemaining > 0,
            ])>
                {{ $selectedWallet->formattedTotalRemaining }}
                <x-active-currency />
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

    {{-- amount row --}}
    <div class="w-full flex items-center justify-between">
        <div class="w-full flex flex-col items-center py-3">
            <h1 class=" text-center flex-1 font-extrabold text-2xl base-text" x-text="amount"></h1>
            <x-input-error :messages="$errors->get('form.amount')" />
        </div>
        <x-svgs.back @click="backspace" class="!w-10 !h-10 primary-text text-3xl " />
    </div>

    <x-calculator />

    {{-- notes date and time --}}
    <x-text-input placeholder="notes..." type="text" wire:model="form.notes" />
    <div class="w-full flex items-center gap-3">
        <x-text-input type="date" wire:model="form.date" />
        <x-text-input type="time" wire:model="form.time" />
    </div>

    {{-- Save Button --}}
    <button class="w-full text-white rounded-lg p-4 bg-gray-900 dark:bg-indigo-800 dark:text-gray-300"
        wire:click="save">Save</button>

    {{-- Modals --}}

    {{-- Wallets Modal --}}
    <x-my-modal modalName="showWalletsModal" closeAction="resetModals()" title="Select Wallet">
        @foreach ($this->availableWalletsList as $wallet)
            <div @click="handleSelection('App\\Models\\Wallet',{{ $wallet }})"
                style="background-color: {{ $wallet->color }}"
                class="flex justify-between cursor-pointer items-center bg-gradient-to-bl from-gray-700/50 to-transparent p-3 white-text">
                <div>{{ $wallet->name }}</div>
                @if ($wallet->totalRemaining !== 0)
                    <div>
                        {{ $wallet->formattedTotalRemaining }}
                        <x-active-currency />
                    </div>
                @endif
            </div>
        @endforeach
    </x-my-modal>

    {{-- Contacts Modal --}}
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
                        {{ $contact->formattedTotalRemaining }}
                        <x-active-currency />
                    </div>
                @endif
            </div>
        @endforeach
    </x-my-modal>

    {{-- Categories Modal --}}
    @foreach (['expense', 'income'] as $type)
        @php
            $modalName = $type == 'income' ? 'showIncomesModal' : 'showExpensesModal';
            $title = $type == 'income' ? 'Select Income' : 'Select Expense';
        @endphp
        <x-my-modal :modalName="$modalName" closeAction="resetModals()" :title="$title">
            <x-slot name="mostUsedItems">
                <div class="flex flex-row-reverse flex-wrap items-center justify-center gap-3">
                    @foreach ($this->mostUsedCategoriesList($type) as $category)
                        <span @click="handleSelection('App\\Models\\Category',{{ $category }})"
                            class="secondary-bg cursor-pointer light-text text-sm font-medium px-3 py-1 rounded">{{ $category->name }}</span>
                    @endforeach
                </div>
            </x-slot>
            <div class="flex flex-col gap-3 base-bg">
                @foreach ($this->parentCategoriesList($type) as $category)
                    <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider overflow-clip">
                        <div @click="handleSelection('App\\Models\\Category',{{ $category }})"
                            class=" p-3 cursor-pointer primary-bg white-text flex items-center justify-between">
                            <div>{{ $category->name }}</div>
                        </div>
                        @forelse ($this->subCategoriesList($category->id) as $sub_category)
                            <div @click="handleSelection('App\\Models\\Category',{{ $sub_category }})"
                                class=" cursor-pointer flex items-center justify-between p-3">
                                <div>{{ $sub_category->name }}</div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                @endforeach
            </div>
        </x-my-modal>
    @endforeach

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
</script>
