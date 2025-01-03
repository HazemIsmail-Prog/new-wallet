<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
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
        'category_id' => [],
        'contact_id' => '',
        'wallet_id' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    public function summary()
    {
        $summary = Transaction::query()
            ->tap(fn($query) => $this->applyFilters($query))
            ->selectRaw("
                SUM(CASE WHEN type IN ('expense', 'loan_to') THEN amount ELSE 0 END) as totalExpenses,
                SUM(CASE WHEN type IN ('income', 'loan_from') THEN amount ELSE 0 END) as totalIncomes
            ")
            ->first();

        $summary->formattedTotalExpenses = number_format($summary->totalExpenses / session('activeCountry')->factor, session('activeCountry')->decimal_points);
        $summary->formattedTotalIncomes = number_format($summary->totalIncomes / session('activeCountry')->factor, session('activeCountry')->decimal_points);

        return $summary;
    }

    #[Computed()]
    public function transactions()
    {
        $transactions = Transaction::query()
            ->with('target:id,name')
            ->with('wallet:id,name')
            ->with(['target' => function ($query) {
                $query->morphWith([
                    Category::class => ['parent_category:id,name'],
                ]);
            }])
            ->tap(fn($query) => $this->applyFilters($query))
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->date->format('Y-m-d');
            });

        // Paginate grouped transactions (5 days per page)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5; // 5 days per page
        $days = $transactions->keys(); // Get the list of days

        // Slice the days to get the current page
        $slicedDays = $days->slice(($currentPage - 1) * $perPage, $perPage);

        // Create a new collection for the paginated results with sum of amounts for each date group
        $paginatedTransactions = $slicedDays->mapWithKeys(function ($day) use ($transactions) {

            $transactionsForDay = $transactions[$day];
            $totalIncomes = $transactionsForDay->whereIn('type', ['income', 'loan_from'])->sum('amount');
            $totalExpenses = $transactionsForDay->whereIn('type', ['expense', 'loan_to'])->sum('amount');
            $formattedTotalExpenses = number_format($totalExpenses, session('activeCountry')->decimal_points);
            $formattedTotalIncomes = number_format($totalIncomes, session('activeCountry')->decimal_points);

            // Return each date group with its transactions and total amount
            return [
                $day => [
                    'transactions' => $transactionsForDay,
                    'totalIncomes' => $totalIncomes,
                    'totalExpenses' => $totalExpenses,
                    'formattedTotalExpenses' => $formattedTotalExpenses,
                    'formattedTotalIncomes' => $formattedTotalIncomes,
                ]
            ];
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
                $q->whereIn('target_id', $this->filters['category_id']);
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
