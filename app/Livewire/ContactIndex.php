<?php

namespace App\Livewire;

use App\Helpers\GetData;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ContactIndex extends Component
{

    #[Computed()]
    public function contacts()
    {
        return GetData::contacts();
    }

    public function render()
    {
        return view('livewire.contact-index');
    }
}
