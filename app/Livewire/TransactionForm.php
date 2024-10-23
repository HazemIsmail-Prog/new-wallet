<?php

namespace App\Livewire;

use App\Livewire\Forms\TransactionForm as FormsTransactionForm;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionForm extends Component
{

    public FormsTransactionForm $form;
    public $selectedWallet;

    #[Computed()]
    public function selectedCountry()
    {
        return session('activeCountry');
    }

    #[Computed()]
    public function categoriesList()
    {
        return Category::query()
            ->leftJoin('transactions', function ($join) {
                $join->on('categories.id', '=', 'transactions.target_id')
                    ->where('transactions.target_type', '=', Category::class);
            })
            ->where('categories.country_id', $this->selectedCountry->id)
            ->select('categories.id', 'categories.name', 'categories.type', 'categories.country_id', 'categories.category_id', DB::raw('COUNT(transactions.id) as transaction_count'))
            ->groupBy('categories.id', 'categories.name', 'categories.type', 'categories.country_id', 'categories.category_id')
            ->get();
    }

    #[Computed()]
    public function walletsList()
    {
        $wallets = Wallet::query()

            ->select('id', 'name', 'country_id','init_amount')

            ->where('country_id', $this->selectedCountry->id)

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

    #[Computed()]
    public function contactsList()
    {
        // Retrieve contacts with totalIncoming and totalOutgoing sums
        $contacts = Contact::query()
            ->where('country_id', $this->selectedCountry->id)
            ->withSum('outgoingTransactions as totalOutgoing', DB::raw('amount'))
            ->withSum('incomingTransactions as totalIncoming', DB::raw('amount'))
            ->get();

        // Add totalRemaining to each contact and sort by absolute value of totalRemaining
        return $contacts->map(function ($contact) {
            $contact->totalRemaining = ($contact->totalIncoming - $contact->totalOutgoing) / $this->selectedCountry->factor;
            return $contact;
        })->sortByDesc(function ($contact) {
            return abs($contact->totalRemaining);
        });
    }

    public function mount(Transaction $transaction, Wallet $wallet)
    {
        if (!$transaction->id) {
            $this->selectedWallet = $this->walletsList->where('id', $wallet->id)->first();
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
