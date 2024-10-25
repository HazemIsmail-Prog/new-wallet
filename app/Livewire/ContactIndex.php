<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ContactIndex extends Component
{
    #[Computed()]
    public function selectedCountry()
    {
        return session('activeCountry');
    }

    #[Computed()]
    public function contacts()
    {
        $contacts = Contact::query()
            ->withSum('outgoingTransactions as totalOutgoing', 'amount')
            ->withSum('incomingTransactions as totalIncoming', 'amount')
            ->get();

        // Add totalRemaining to each contact and sort by absolute value of totalRemaining
        return $contacts->map(function ($contact) {
            $contact->totalRemaining = ($contact->totalIncoming - $contact->totalOutgoing) / $this->selectedCountry->factor;
            $contact->formattedTotalRemaining = number_format(abs($contact->totalRemaining), $this->selectedCountry->decimal_points);
            return $contact;
        })->sortByDesc(function ($contact) {
            return abs($contact->totalRemaining);
        });
    }


    public function render()
    {
        return view('livewire.contact-index');
    }
}
