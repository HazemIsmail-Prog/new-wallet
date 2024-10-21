<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CategoryIndex extends Component
{
    #[Computed()]
    public function selectedCountry()
    {
        return Country::find(Auth::user()->last_selected_country_id);
    }

    #[Computed()]
    public function categories()
    {

        // Retrieve categories with totalIncoming and totalOutgoing sums
        $categories = Category::query()
            ->where('country_id', $this->selectedCountry->id)
            ->withSum('outgoingTransactions as totalOutgoing', DB::raw('amount'))
            ->withSum('incomingTransactions as totalIncoming', DB::raw('amount'))
            // ->whereDoesntHave('parent_category')
            ->get();

        // Add totalRemaining to each category and sort by absolute value of totalRemaining
        return $categories->map(function ($category) {
            $category->totalRemaining = abs($category->totalIncoming - $category->totalOutgoing) / $this->selectedCountry->factor;
            return $category;
        })
        // ->sortByDesc(function ($category) {
        //     return abs($category->totalRemaining);
        // })
        ;
    }

    public function render()
    {
        return view('livewire.category-index');
    }
}
