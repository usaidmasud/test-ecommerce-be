<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $perPage = request()->per_page;
        $items = Transaction::with(['product', 'user'])->byUser(auth()->user()->id)->oldest();
        if (!is_null($search)) {
            $items->search($search);
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
        try {
            DB::beginTransaction();
            $credentials['user_id'] = auth()->user()->id;
            $obj = Transaction::create($credentials);
            DB::commit();
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
        try {
            DB::beginTransaction();
            $credentials['user_id'] = auth()->user()->id;
            $obj->update($credentials);
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
