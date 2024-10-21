<?php

namespace App\Livewire;

use App\Models\Contact;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ContactIndex extends Component
{
    #[Computed()]
    public function selectedCountry()
    {
        return Country::find(Auth::user()->last_selected_country_id);
    }

    #[Computed()]
    public function contacts()
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


    public function render()
    {
        return view('livewire.contact-index');
    }
}
