<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
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
        return session('activeCountry');
    }

    public function applyFilters(Builder $query)
    {
        return $query
            ->when($this->filters['search'], function (Builder $q) {
                $q->where('notes', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when($this->filters['start_date'], function (Builder $q) {
                $q->whereDate('date', '>=', $this->filters['start_date']);
            })
            ->when($this->filters['end_date'], function (Builder $q) {
                $q->whereDate('date', '<=', $this->filters['end_date']);
            })
        ;
    }

    #[Computed()]
    public function categories()
    {
        $categories = Category::query()
            ->where('type', $this->filters['type'])
            ->withSum(['transactions as total' => function (Builder $q) {
                $q->tap(fn($query) => $this->applyFilters($query));
            }], DB::raw('amount / ' . $this->selectedCountry->factor))
            ->get();

        // loop to get sum('total') of sub categories for each category
        $categories = $categories->map(function ($category) use ($categories) {
            $category->sub_categories_total =
                $category->category_id
                ? 0
                : $categories->where('category_id', $category->id)->sum('total');
            return $category;
        });

        // loop to get grand total for each parent category
        // then get formatted amount for all categories
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
