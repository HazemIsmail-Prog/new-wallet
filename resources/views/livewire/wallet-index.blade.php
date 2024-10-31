<x-slot name="header">
    <h2 class="flex items-center justify-between font-semibold text-xl base-text leading-tight">
        <div>{{ __('Wallets') }}</div>
        <div id="new"></div>
    </h2>
</x-slot>

<div x-data="form()" class="p-3 flex flex-col gap-3">

    <template x-teleport="#new">
        <button class=" text-sm uppercase primary-bg py-2 px-6 white-text rounded-md"
            x-on:click="showModal()">Add</button>
    </template>


    <div class="flex gap-3 text-gray-800 dark:text-gray-400">
        @if ($this->wallets)
            <div class="flex-1">
                <h1 class=" font-extralight text-sm">Total Available</h1>
                <h1 class="text-start text-3xl font-extrabold">
                    {{ number_format($this->wallets->sum('totalRemaining'), session('activeCountry')->decimal_points) }}
                    <x-active-currency />
                </h1>
            </div>
        @endif
    </div>


    @foreach ($this->wallets as $wallet)
        <div class=" flex items-center gap-3">
            <a wire:navigate href="{{ route('transaction.form.new', $wallet) }}"
                style="background-color: {{ $wallet->color }}"
                class="flex flex-1 justify-between items-center bg-gradient-to-bl from-gray-700/50 to-transparent  rounded-xl p-8 h-36 dark:shadow-none shadow-md shadow-gray-500 text-white">
                <div>{{ $wallet->name }}</div>
                <div class="font-normal text-xs">
                    <div class=" text-right">Available Amount</div>
                    <div class=" text-2xl font-extrabold">
                        {{ $wallet->formattedTotalRemaining }}
                        <x-active-currency />
                    </div>
                </div>
            </a>
            <button x-data="{ show: false, deleteConfirmation: false }" class=" relative"
                x-on:click.outside="show = false ;deleteConfirmation = false"
                x-on:click="show = !show;deleteConfirmation = false">
                <x-svgs.vertical-dots class="base-text" />
                <div x-cloak x-show="show"
                    class=" secondary-bg base-text divide-y gray-divider absolute top-0 right-8 border gray-border shadow-md z-10 overflow-hidden rounded-lg">
                    <a wire:navigate class="block p-3 w-full  whitespace-nowrap cursor-pointer"
                        href="{{ route('transaction.index', ['filters[wallet_id]' => $wallet->id]) }}">
                        View Transactions
                    </a>
                    <input x-on:click="showModal({{ $wallet }})" class="p-3 w-full cursor-pointer" type="button"
                        value="Edit">
                    <template x-if="!deleteConfirmation">
                        <input x-on:click.stop="deleteConfirmation=true" class="p-3 w-full cursor-pointer"
                            type="button" value="Delete">
                    </template>
                    <template x-if="deleteConfirmation">
                        <div class=" flex items-center">
                            <input wire:click="delete({{ $wallet }})" class="p-3 w-full cursor-pointer"
                                type="button" value="Confirm">
                            <input x-on:click.stop="deleteConfirmation = false" class="p-3 w-full cursor-pointer"
                                type="button" value="Cancel">
                        </div>
                    </template>
                </div>
            </button>
        </div>
    @endforeach

    <x-my-modal modalName="formModal" closeAction="closeModal()">
        <form x-on:submit.prevent="save" class=" flex flex-col gap-3 items-center justify-center base-bg p-1">
            <x-text-input x-ref="nameInput" required x-model="form.name" placeholder="Name" />
            <x-text-input required x-model="form.init_amount" placeholder="Initial Amount" />
            <x-text-input type="color" required x-model="form.color" placeholder="Color" />
            <button class="primary-bg py-2 px-4 white-text rounded-md">Save</button>
        </form>
    </x-my-modal>

</div>

<script>
    function form() {
        return {
            formModal: false,
            modalTitle: '',
            form: @entangle('form'),

            init() {
                Livewire.on('modalClosed', () => this.closeModal());
            },

            showModal(wallet = null) {
                if (wallet) {
                    this.modalTitle = `Edit ${wallet.name}`;
                    this.form.id = wallet.id;
                    this.form.name = wallet.name;
                    this.form.init_amount = wallet.init_amount;
                    this.form.color = wallet.color;
                } else {
                    this.modalTitle = 'New Wallet';
                }
                this.formModal = true;
                this.$nextTick(() => this.$refs.nameInput.focus());
            },

            closeModal() {
                this.formModal = false;
                this.resetForm();
            },

            resetForm() {
                this.form.id = null;
                this.form.name = '';
                this.form.init_amount = '';
                this.form.color = '';
            },

            save() {
                @this.save();
            },
        };
    }
</script>
