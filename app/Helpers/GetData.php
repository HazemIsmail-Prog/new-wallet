<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetData
{
    public static function wallets()
    {
        $wallets = Wallet::query()

            ->withSum(['transactions as walletOutgoings' => function (Builder $q) {
                $q->whereIn('type', ['expense', 'loan_to', 'transfer']);
            }], DB::raw('amount'))

            ->withSum(['transactions as walletIncomings' => function (Builder $q) {
                $q->whereIn('type', ['income', 'loan_from']);
            }], DB::raw('amount'))

            ->withSum(['incomingTransactions as incomingTransfers' => function (Builder $q) {
                // add condition if needed
            }], DB::raw('amount'))

            ->get();

        return $wallets->map(function ($wallet) {
            $wallet->totalRemaining =
                (
                    $wallet->init_amount
                    + $wallet->walletIncomings
                    + $wallet->incomingTransfers
                    - $wallet->walletOutgoings
                ) / session('activeCountry')->factor;

            $wallet->formattedTotalRemaining = number_format(abs($wallet->totalRemaining), session('activeCountry')->decimal_points);

            return $wallet;
        });
    }

    public static function contacts()
    {
        $contacts = Contact::query()
            ->withSum('outgoingTransactions as totalOutgoing', 'amount')
            ->withSum('incomingTransactions as totalIncoming', 'amount')
            ->get();

        // Add totalRemaining to each contact and sort by absolute value of totalRemaining
        return $contacts->map(function ($contact) {
            $contact->totalRemaining = ($contact->totalIncoming - $contact->totalOutgoing) / session('activeCountry')->factor;
            $contact->formattedTotalRemaining = number_format(abs($contact->totalRemaining), session('activeCountry')->decimal_points);
            return $contact;
        })->sortByDesc(function ($contact) {
            return abs($contact->totalRemaining);
        });
    }

    public static function categories($filters)
    {
        $categories = Category::query()
            // ->where('type', $filters['type'])
            ->withSum(['transactions as total' => function (Builder $q) use ($filters) {
                $q->tap(fn($query) => self::categoriesFilter($query, $filters));
            }], DB::raw('amount / ' . session('activeCountry')->factor))
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
            $category->formatted_total = number_format($category->total, session('activeCountry')->decimal_points);
            $category->formatted_sub_categories_total = number_format($category->sub_categories_total, session('activeCountry')->decimal_points);
            $category->formatted_grand_total = number_format($category->grand_total, session('activeCountry')->decimal_points);

            return $category;
        });

        return $categories
            ->sortByDesc('total')
            ->sortByDesc('grand_total');
    }

    private static function categoriesFilter(Builder $query, $filters)
    {
        return $query
            ->when($filters['search'], function (Builder $q) use ($filters) {
                $q->where('notes', 'like', '%' . $filters['search'] . '%');
            })
            ->when($filters['start_date'], function (Builder $q) use ($filters) {
                $q->whereDate('date', '>=', $filters['start_date']);
            })
            ->when($filters['end_date'], function (Builder $q) use ($filters) {
                $q->whereDate('date', '<=', $filters['end_date']);
            })
        ;
    }

    public static function categoriesListForModals()
    {
        return Category::query()
            ->leftJoin('transactions', function ($join) {
                $join->on('categories.id', '=', 'transactions.target_id')
                    ->where('transactions.target_type', '=', Category::class);
            })
            ->select('categories.id', 'categories.name', 'categories.type', 'categories.country_id', 'categories.category_id', DB::raw('COUNT(transactions.id) as transaction_count'))
            ->groupBy('categories.id', 'categories.name', 'categories.type', 'categories.country_id', 'categories.category_id')
            ->get();
    }

    public static function countries()
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
}
