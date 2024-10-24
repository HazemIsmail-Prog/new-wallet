<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WalletIndex extends Component
{
    #[Computed()]
    public function selectedCountry()
    {
        return session('activeCountry');
    }

    #[Computed()]
    public function wallets()
    {
        $wallets = Wallet::query()

            ->withSum(['transactions as walletOutgoings' => function (Builder $q) {
                $q->whereIn('type', ['expense', 'loan_to', 'transfer']);
            }], DB::raw('amount'))

            ->withSum(['transactions as walletIncomings' => function (Builder $q) {
                $q->whereIn('type', ['income', 'loan_from']);
            }], DB::raw('amount'))

            ->withSum(['incomingTransactions as incomingTransfers' => function (Builder $q) {
                // add condition if needed
            }], DB::raw('amount'))

            ->get();

        return $wallets->map(function ($wallet) {
            $wallet->totalRemaining =
                (
                    $wallet->init_amount
                    + $wallet->walletIncomings
                    + $wallet->incomingTransfers
                    - $wallet->walletOutgoings
                ) / $this->selectedCountry->factor;
            return $wallet;
        });
    }

    public function render()
    {
        return view('livewire.wallet-index');
    }
}
