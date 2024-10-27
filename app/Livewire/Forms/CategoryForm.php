<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{

    public $id;
    #[Validate('required')]
    public $name;
    #[Validate('required')]
    public $type;
    public $category_id;


    public function updateOrCreate()
    {
        $this->validate();
        Category::updateOrCreate(['id'=>$this->id],$this->all());
        $this->reset();
    }
}
