<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionIndex extends Component
{
    use WithPagination;

    #[Url()]
    public array $filters = [
        'search' => '',
        'start_date' => '',
        'end_date' => '',
        'category_id' => '',
        'contact_id' => '',
        'wallet_id' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    public function selectedCountry()
    {
        return Country::find(Auth::user()->last_selected_country_id);
    }

    #[Computed()]
    public function summary()
    {
        return Transaction::query()
            ->tap(fn($query) => $this->applyFilters($query))
            ->selectRaw("
                SUM(CASE WHEN type IN ('expense', 'loan_to') THEN amount ELSE 0 END) as totalExpenses,
                SUM(CASE WHEN type IN ('income', 'loan_from') THEN amount ELSE 0 END) as totalIncomes
            ")
            ->first();
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
            ->tap(fn($query) => $this->applyFilters($query))
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
            ->when($this->filters['category_id'], function (Builder $q) {
                $q->where('target_type', Category::class);
                $q->where('target_id', $this->filters['category_id']);
            })
            ->when($this->filters['contact_id'], function (Builder $q) {
                $q->where('target_type', Contact::class);
                $q->where('target_id', $this->filters['contact_id']);
            })
            ->when($this->filters['wallet_id'], function (Builder $q) {
                $q->where('wallet_id', $this->filters['wallet_id']);
                $q->orWhere(function (Builder $q) {
                    $q->where('target_type', Wallet::class);
                    $q->where('target_id', $this->filters['wallet_id']);
                });
            })
        ;
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
