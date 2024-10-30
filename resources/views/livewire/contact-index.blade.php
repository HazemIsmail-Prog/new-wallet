<x-slot name="header">
    <h2 class="flex items-center justify-between font-semibold text-xl base-text leading-tight">
        <div>{{ __('Contacts') }}</div>
        <div id="new"></div>
    </h2>
</x-slot>

<div x-data="form()" class="p-3 flex flex-col gap-3">

    <template x-teleport="#new">
        <button class=" text-sm uppercase primary-bg py-2 px-6 white-text rounded-md"
            x-on:click="showModal()">Add</button>
    </template>

    <x-text-input placeholder="Search..." type="text" wire:model.live="filters.search" />

    <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-visible">
        @foreach ($this->contacts as $contact)
            <div class="flex items-center justify-between cursor-pointer p-3 base-text secondary-bg">
                <div>{{ $contact->name }}</div>
                <div class="flex items-center gap-2">
                    @if ($contact->totalRemaining !== 0)
                        <div @class([
                            'font-extrabold',
                            'red-text' => $contact->totalRemaining < 0,
                            'green-text' => $contact->totalRemaining > 0,
                        ])>
                            {{ $contact->formattedTotalRemaining }}
                            <x-active-currency />
                        </div>
                    @endif
                    <button x-data="{ show: false, deleteConfirmation: false }" class=" relative"
                        x-on:click.outside="show = false ;deleteConfirmation = false"
                        x-on:click="show = !show;deleteConfirmation = false">
                        <x-svgs.vertical-dots />
                        <div x-cloak x-show="show"
                            class=" secondary-bg base-text divide-y gray-divider absolute top-0 right-8 border gray-border shadow-md z-10 overflow-hidden rounded-lg">
                            <a wire:navigate class="block p-3 w-full  whitespace-nowrap cursor-pointer"
                                href="{{ route('transaction.index', ['filters[contact_id]' => $contact->id]) }}">
                                View Transactions
                            </a>
                            <input x-on:click="showModal({{ $contact }})" class="p-3 w-full cursor-pointer"
                                type="button" value="Edit">
                            <template x-if="!deleteConfirmation">
                                <input x-on:click.stop="deleteConfirmation=true" class="p-3 w-full cursor-pointer"
                                    type="button" value="Delete">
                            </template>
                            <template x-if="deleteConfirmation">
                                <div class=" flex items-center">
                                    <input wire:click="delete({{ $contact }})" class="p-3 w-full cursor-pointer"
                                        type="button" value="Confirm">
                                    <input x-on:click.stop="deleteConfirmation = false"
                                        class="p-3 w-full cursor-pointer" type="button" value="Cancel">
                                </div>
                            </template>
                        </div>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <x-my-modal modalName="formModal" closeAction="closeModal()">
        <form x-on:submit.prevent="save" class=" flex flex-col gap-3 items-center justify-center base-bg p-1">
            <x-text-input x-ref="nameInput" required x-model="form.name" placeholder="Name" />
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

            showModal(contact = null) {
                if (contact) {
                    this.modalTitle = `Edit ${contact.name}`;
                    this.form.id = contact.id;
                    this.form.name = contact.name;
                } else {
                    this.modalTitle = 'New Contact';
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
            },

            save() {
                @this.save();
            },
        };
    }
</script>
