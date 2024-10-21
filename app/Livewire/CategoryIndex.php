<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CategoryIndex extends Component
{

    public array $filters = [
        'type' => 'expense',
        'search' => '',
        'start_date' => '',
        'end_date' => '',
    ];

    #[Computed()]
    public function selectedCountry()
    {
        return Country::find(Auth::user()->last_selected_country_id);
    }

    #[Computed()]
    public function categories()
    {
        $categories = Category::query()
            ->where('country_id', $this->selectedCountry->id)
            ->where('type', $this->filters['type'])
            ->withSum(['transactions as total' => function (Builder $q) {
                $q->when($this->filters['start_date'], function (Builder $q) {
                    $q->whereDate('date', '>=', $this->filters['start_date']);
                });
                $q->when($this->filters['end_date'], function (Builder $q) {
                    $q->whereDate('date', '<=', $this->filters['end_date']);
                });
            }], DB::raw('amount / ' . $this->selectedCountry->factor))
            ->get();

        $categories = $categories->map(function ($category) use ($categories) {
            $category->sub_categories_total =
                $category->category_id
                ? 0
                : $categories->where('category_id', $category->id)->sum('total');
            return $category;
        });

        $categories = $categories->map(function ($category) {
            $category->grand_total = $category->total + $category->sub_categories_total;

            // formatters
            $category->formatted_total = number_format($category->total, $this->selectedCountry->decimal_points);
            $category->formatted_sub_categories_total = number_format($category->sub_categories_total, $this->selectedCountry->decimal_points);
            $category->formatted_grand_total = number_format($category->grand_total, $this->selectedCountry->decimal_points);

            return $category;
        });

        return $categories
            ->sortByDesc('total')
            ->sortByDesc('grand_total');
    }

    public function render()
    {
        return view('livewire.category-index');
    }
}
