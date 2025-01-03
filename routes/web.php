<?php

use App\Livewire\CategoryIndex;
use App\Livewire\ContactIndex;
use App\Livewire\CountryIndex;
use App\Livewire\TransactionForm;
use App\Livewire\TransactionIndex;
use App\Livewire\WalletIndex;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

Route::middleware([
    'auth',
    'verified',
])->group(function () {

    Route::get('/', function () {
        return redirect()->route('wallet.index');
    })->name('dashboard');

    Route::get('/countries', CountryIndex::class)->name('country.index');

    Route::middleware(['checkCountry'])->group(function(){
        Route::get('/contacts', ContactIndex::class)->name('contact.index');
        Route::get('/categories', CategoryIndex::class)->name('category.index');
        Route::get('/wallets', WalletIndex::class)->name('wallet.index');
        Route::get('transaction-from/{transaction}',TransactionForm::class)->name('transaction.form');
        Route::get('transaction-from-new/{wallet}',TransactionForm::class)->name('transaction.form.new');
        Route::get('/transactions', TransactionIndex::class)->name('transaction.index');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
