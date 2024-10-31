<?php

namespace App\Livewire;

use App\Helpers\GetData;
use App\Livewire\Forms\WalletForm;
use App\Models\Wallet;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WalletIndex extends Component
{

    public WalletForm $form;


    #[Computed()]
    public function wallets()
    {
        return GetData::wallets();
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('modalClosed'); // Emit an event to close the modal
    }

    public function delete(Wallet $wallet)
    {
        $wallet->delete();
    }

    public function render()
    {
        return view('livewire.wallet-index');
    }
}
