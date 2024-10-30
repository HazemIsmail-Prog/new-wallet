<?php

namespace App\Livewire;

use App\Helpers\GetData;
use App\Livewire\Forms\ContactForm;
use App\Models\Contact;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ContactIndex extends Component
{

    public array $filters = [
        'search' => '',
    ];

    public ContactForm $form;

    #[Computed()]
    public function contacts()
    {
        return GetData::contacts($this->filters);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('modalClosed'); // Emit an event to close the modal
    }

    public function delete(Contact $contact)
    {
        $contact->delete();
    }

    public function render()
    {
        return view('livewire.contact-index');
    }
}
