<?php

namespace App\Livewire;

use App\Helpers\GetData;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WalletIndex extends Component
{
    #[Computed()]
    public function wallets()
    {
        return GetData::wallets();
    }

    public function render()
    {
        return view('livewire.wallet-index');
    }
}
