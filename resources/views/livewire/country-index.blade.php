<x-slot name="header">
    <h2 class="flex items-center justify-between font-semibold text-xl base-text leading-tight">
        <div>{{ __('Countries') }}</div>
        <div id="new"></div>
    </h2>
</x-slot>

<div x-data="form()" class="p-3 flex flex-col gap-3">

    <template x-teleport="#new">
        <button class=" text-sm uppercase primary-bg py-2 px-6 white-text rounded-md"
            x-on:click="showModal()">Add</button>
    </template>

    <div class="rounded-lg secondary-bg base-text divide-y-2 gray-divider shadow-lg overflow-visible">

        @foreach ($this->countries as $country)
            <div class="flex items-center justify-between cursor-pointer p-3 base-text secondary-bg">
                <div class="flex items-center gap-2">
                    <div>
                            <x-svgs.check  wire:click="setCountry({{ $country->id }})" class="w-6 h-6 {{ session('activeCountry')->id == $country->id ? 'green-text' : 'light-text' }} " />
                    </div>
                    <div>{{ $country->name }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <div @class([
                        'font-extrabold',
                        'red-text' => $country->totalRemaining < 0,
                        'green-text' => $country->totalRemaining > 0,
                    ])>
                        {{ $country->formattedTotalRemaining }}
                        <span class=" uppercase text-xs font-thin">{{ $country->currency }}</span>
                    </div>
                    <button x-data="{ show: false, deleteConfirmation: false }" class=" relative"
                        x-on:click.outside="show = false ;deleteConfirmation = false"
                        x-on:click="show = !show;deleteConfirmation = false">
                        <x-svgs.vertical-dots />
                        <div x-cloak x-show="show"
                            class=" secondary-bg base-text divide-y gray-divider absolute top-0 right-8 border gray-border shadow-md z-10 overflow-hidden rounded-lg">
                            <input x-on:click="showModal({{ $country }})" class="p-3 w-full cursor-pointer"
                                type="button" value="Edit">
                            <template x-if="!deleteConfirmation">
                                <input x-on:click.stop="deleteConfirmation=true" class="p-3 w-full cursor-pointer"
                                    type="button" value="Delete">
                            </template>
                            <template x-if="deleteConfirmation">
                                <div class=" flex items-center">
                                    <input wire:click="delete({{ $country }})" class="p-3 w-full cursor-pointer"
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
            <x-text-input required x-model="form.currency" placeholder="Currency" />
            <x-text-input required x-model="form.decimal_points" placeholder="Decimal Points" />
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

            showModal(country = null) {
                if (country) {
                    this.modalTitle = `Edit ${country.name}`;
                    this.form.id = country.id;
                    this.form.name = country.name;
                    this.form.currency = country.currency;
                    this.form.decimal_points = country.decimal_points;
                } else {
                    this.modalTitle = 'New Country';
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
                this.form.currency = '';
                this.form.decimal_points = '';
            },

            save() {
                @this.save();
            },
        };
    }
</script>
