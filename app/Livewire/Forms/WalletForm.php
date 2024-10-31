<?php

namespace App\Livewire\Forms;

use App\Models\Wallet;
use Livewire\Attributes\Validate;
use Livewire\Form;

class WalletForm extends Form
{
    public $id;

    #[Validate('required')]
    public $name;
    
    #[Validate('required')]
    public $init_amount;

    #[Validate('required')]
    public $color;


    public function updateOrCreate()
    {
        $this->validate();
        Wallet::updateOrCreate(['id'=>$this->id],$this->all());
        $this->reset();
    }
}
