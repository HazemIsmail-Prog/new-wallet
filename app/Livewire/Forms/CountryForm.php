<?php

namespace App\Livewire\Forms;

use App\Models\Country;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CountryForm extends Form
{
    public $id;

    #[Validate('required')]
    public $name;

    #[Validate('required')]
    public $currency;

    #[Validate('required')]
    public $decimal_points;

    public function updateOrCreate()
    {
        $this->validate();
        if ($this->id) {
            Country::updateOrCreate(['id' => $this->id], $this->only('name'));
        } else {
            Country::updateOrCreate(['id' => $this->id], $this->all());
        }
        $this->reset();
    }
}
