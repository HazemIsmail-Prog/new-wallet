<?php

namespace App\Livewire;

use App\Helpers\GetData;
use App\Livewire\Forms\CountryForm;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CountryIndex extends Component
{

    public CountryForm $form;

    #[Computed()]
    public function countries()
    {
        return GetData::countries();
    }

    public function setCountry($countryId) {
        $authUser = User::find(Auth::id());
        $authUser->last_selected_country_id = $countryId;
        $authUser->save();
        return $this->redirect(route('wallet.index'), navigate: true);
    }

    public function save()
    {
        $this->form->updateOrCreate();
        $this->dispatch('modalClosed'); // Emit an event to close the modal
    }

    public function delete(Country $country)
    {
        $country->delete();
    }

    public function render()
    {
        return view('livewire.country-index');
    }
}
