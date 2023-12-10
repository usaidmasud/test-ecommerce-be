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
}
