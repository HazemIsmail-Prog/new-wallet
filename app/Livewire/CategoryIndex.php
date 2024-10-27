<?php

namespace App\Livewire;

use App\Helpers\GetData;
use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
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

    public CategoryForm $form;

    #[Computed()]
    public function categories()
    {
        return GetData::categories($this->filters);
    }

    #[Computed()]
    public function parentCategoriesList()
    {
        return $this->categories->where('category_id', null);
    }

    #[Computed()]
    public function subCategoriesList($categoryId)
    {
        return $this->categories->where('category_id', $categoryId);
    }

    public function save()
    {
        $this->form->type = $this->filters['type'];
        $this->form->updateOrCreate();
        $this->dispatch('modalClosed'); // Emit an event to close the modal
    }

    public function delete(Category $category)
    {
        $category->delete();
    }

    public function render()
    {
        return view('livewire.category-index');
    }
}
