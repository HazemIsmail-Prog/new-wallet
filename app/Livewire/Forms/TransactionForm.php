<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public $id;
    public $type = 'expense';
    public $country_id;
    public $wallet_id;
    public $target_id;
    public $target_type;
    public $amount = 0;
    public $date;
    public $time;
    public $notes;

    public function rules()
    {
        return [
            'type' => 'required',
            'wallet_id' => 'required',
            'target_id' => 'required',
            'target_type' => 'required',
            'amount' => 'required|numeric|gt:0',
            'date' => 'required',
            'time' => 'required',
            'notes' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'Enter your amount',
            'amount.gt' => 'Enter your amount',
            'amount.numeric' => 'Invalid amount',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();

        $this->country_id = Auth::user()->last_selected_country_id;

        Transaction::updateOrCreate(['id' => $this->id ?? null], $this->all());
    }
}
