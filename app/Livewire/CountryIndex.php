<?php

namespace App\Livewire;

use App\Helpers\GetData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CountryIndex extends Component
{

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

    public function render()
    {
        return view('livewire.country-index');
    }
}
