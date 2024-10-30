<?php

namespace App\Livewire\Forms;

use App\Models\Contact;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ContactForm extends Form
{

    public $id;
    #[Validate('required')]
    public $name;


    public function updateOrCreate()
    {
        $this->validate();
        Contact::updateOrCreate(['id'=>$this->id],$this->all());
        $this->reset();
    }
}
