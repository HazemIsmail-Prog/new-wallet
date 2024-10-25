<?php

namespace App\Livewire;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CountryIndex extends Component
{

    #[Computed()]
    public function countries()
    {
        $countries = Country::query()
            ->where('user_id', Auth::id())
            ->withSum('outgoingTransactions as totalOutgoing', 'amount')
            ->withSum('incomingTransactions as totalIncoming', 'amount')
            ->withSum('wallets as walletsInitAmount', 'init_amount')
            ->get();

        // Add totalRemaining to each contact and sort by absolute value of totalRemaining
        return $countries->map(function ($country) {
            $country->totalRemaining = ($country->walletsInitAmount + $country->totalIncoming - $country->totalOutgoing) / $country->factor;
            $country->formattedTotalRemaining = number_format(abs($country->totalRemaining), $country->decimal_points);
            return $country;
        });
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
