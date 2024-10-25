<?php

namespace App\Livewire;

use App\Helpers\GetData;
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
    public function categories()
    {
        return GetData::categories($this->filters);
    }

    #[Computed()]
    public function parentCategoriesList() {
        return $this->categories->where('category_id', null);
    }

    #[Computed()]
    public function subCategoriesList($categoryId) {
        return $this->categories->where('category_id', $categoryId);
    }

    public function render()
    {
        return view('livewire.category-index');
    }
}
