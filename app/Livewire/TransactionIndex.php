<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Country;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionIndex extends Component
{
    public array $filters = [
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
    public function transactions()
    {
        $transactions = Transaction::query()
            ->where('country_id', $this->selectedCountry()->id)
            ->with('target')
            ->with('wallet')
            ->with(['target' => function ($query) {
                $query->morphWith([
                    Category::class => ['parent_category'],
                ]);
            }])
            ->when($this->filters['search'], function (Builder $q) {
                $q->where('notes', 'like', '%' . $this->filters['search'] . '%');
            })
            ->when($this->filters['start_date'], function (Builder $q) {
                $q->whereDate('date', '>=', $this->filters['start_date']);
            })
            ->when($this->filters['end_date'], function (Builder $q) {
                $q->whereDate('date', '<=', $this->filters['end_date']);
            })
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->date->format('Y-m-d');
            });

        // Paginate grouped transactions (10 days per page)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5; // 5 days per page
        $days = $transactions->keys(); // Get the list of days

        // Slice the days to get the current page
        $slicedDays = $days->slice(($currentPage - 1) * $perPage, $perPage);

        // Create a new collection for the paginated results
        $paginatedTransactions = $slicedDays->mapWithKeys(function ($day) use ($transactions) {
            return [$day => $transactions[$day]]; // Keep the transactions for each day
        });

        return new LengthAwarePaginator(
            $paginatedTransactions,
            $transactions->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    public function delete(Transaction $transaction)
    {
        $transaction->delete();
    }


    public function render()
    {
        return view('livewire.transaction-index');
    }
}
