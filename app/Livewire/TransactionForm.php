<?php

namespace App\Livewire;

use App\Helpers\GetData;
use App\Livewire\Forms\TransactionForm as FormsTransactionForm;
use App\Models\Transaction;
use App\Models\Wallet;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionForm extends Component
{
    public FormsTransactionForm $form;
    public $selectedWallet;

    #[Computed()]
    public function categoriesList()
    {
        return GetData::categoriesListForModals();
    }

    #[Computed()]
    public function mostUsedCategoriesList($type) {
        return $this->categoriesList->where('type', $type)->sortByDesc('transaction_count')->take(10);
    }
 
    #[Computed()]
    public function parentCategoriesList($type) {
        return $this->categoriesList->where('type', $type)->where('category_id', null);
    }

    #[Computed()]
    public function subCategoriesList($categoryId) {
        return $this->categoriesList->where('category_id', $categoryId);
    }

    #[Computed()]
    public function walletsList()
    {
        return GetData::wallets();
    }

    #[Computed()]
    public function availableWalletsList()  {
        return $this->walletsList->where('id', '!=', $this->selectedWallet->id);
    }

    #[Computed()]
    public function contactsList()
    {
        return GetData::contacts();
    }

    public function mount(Transaction $transaction, Wallet $wallet)
    {
        if (!$transaction->id) {
            $this->selectedWallet = $this->walletsList->findOrFail($wallet->id);
            $this->form->wallet_id = $wallet->id;
            $this->form->date = date('Y-m-d');
            $this->form->time = now()->format('H:i');
        } else {
            //edit
            $this->selectedWallet = $this->walletsList->where('id', $transaction->wallet_id)->first();
            $this->form->fill($transaction);
            $this->form->date = $transaction->date->format('Y-m-d');
            $this->form->time = $transaction->time->format('H:i');
        }
    }

    public function save()
    {
        $this->form->updateOrCreate();
        return $this->redirect(route('wallet.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.transaction-form');
    }
}
