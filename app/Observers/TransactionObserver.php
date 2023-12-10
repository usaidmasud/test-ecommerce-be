<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $product = Product::where('id', $transaction->product_id)->first();
        $product->decrement('stock', $transaction->qty);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // ...
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        // ...
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        // ...
    }

    /**
     * Handle the Transaction "forceDeleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        // ...
    }
}
