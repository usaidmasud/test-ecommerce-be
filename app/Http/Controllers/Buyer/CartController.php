<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use ApiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $perPage = request()->per_page;
        $items = Cart::with(['product', 'user'])->oldest();
        if (!is_null($search)) {
            $items->search($search);
        }
        if (!is_null($perPage)) {
            return CartResource::collection($items->paginate($perPage));
        } else {
            return CartResource::collection($items->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        $credentials = $request->all();
        try {
            DB::beginTransaction();
            $credentials['user_id'] = auth()->user()->id;
            $obj = Cart::create($credentials);
            DB::commit();
            return new CartResource($obj);
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
        $find = Cart::with(['product','user'])->where('id', $id)->first();
        if (is_null($find)) {
            return $this->responseNotFound();
        }
        return new CartResource($find);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, string $id)
    {
        $obj = Cart::find($id);
        $credentials = $request->all();
        try {
            DB::beginTransaction();
            $credentials['user_id'] = auth()->user()->id;
            $obj->update($credentials);
            DB::commit();
            return new CartResource($obj);
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
        $find = Cart::find($id);
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
