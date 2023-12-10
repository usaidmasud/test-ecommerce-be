<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Traits\ApiTrait;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    use ApiTrait, HelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $status = request()->status;
        $perPage = request()->per_page;
        $items = Transaction::with(['product','user'])->oldest();
        if (!is_null($search)) {
            $items->search($search);
        }
        if (!is_null($status)) {
            $items->status($status);
        }
        if (!is_null($perPage)) {
            return TransactionResource::collection($items->paginate($perPage));
        } else {
            return TransactionResource::collection($items->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $credentials = $request->all();
        $product = Product::where('id', $credentials['product_id'])->first();
        // check product
        if (!$product) {
            return $this->responseNotFound('Product not found');
        }
        // check stock
        if ($credentials['qty'] > $product->stock ) {
            return $this->responseNotAccept('Stock not available');
        }
        try {
            $this->lockTable('transactions');
            DB::beginTransaction();
            $credentials['user_id'] = auth()->user()->id;
            $obj = Transaction::create([
                "product_id" => $credentials['product_id'],
                "user_id" => auth()->user()->id,
                "total_price" => $product->price * $credentials['qty'],
                "qty" => $credentials['qty'],
            ]);
            // update product stock
            $product->increment('stock', $obj->qty);
            DB::commit();
            $this->unlockTable();
            return new TransactionResource($obj);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseNotAccept($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = Transaction::with(['product','user'])->where('id', $id)->first();
        if (is_null($find)) {
            return $this->responseNotFound();
        }
        return new TransactionResource($find);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, string $id)
    {
        $obj = Transaction::find($id);
        $credentials = $request->all();
        $product = Product::where('id', $credentials['product_id'])->first();
        // check product
        if (!$product) {
            return $this->responseNotFound('Product not found');
        }
        // check stock
        if ($credentials['qty'] > ($product->stock + $obj->qty) ) {
            return $this->responseNotAccept('Stock not available');
        }
        try {
            DB::beginTransaction();
            // kembalikan stok yang sebelumnya dibeli
            $product->increment('stock', $obj->qty);
            $obj->update([
                "product_id" => $credentials['product_id'],
                "user_id" => auth()->user()->id,
                "total_price" => $product->price * $credentials['qty'],
                "status" => $credentials['status'],
                "qty" => $credentials['qty'],
            ]);
            // update product stock
            $product->decrement('stock', $obj->qty);
            DB::commit();
            return new TransactionResource($obj);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseNotAccept($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Transaction::find($id);
        try {
            DB::beginTransaction();
            $find->delete();
            DB::commit();
            return $this->responseNoContent();
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseNotAccept($th->getMessage());
        }
    }
}
