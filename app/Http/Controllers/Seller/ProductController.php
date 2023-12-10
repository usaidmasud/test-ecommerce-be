<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ApiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $perPage = request()->per_page;
        $items = Product::oldest();
        if (!is_null($search)) {
            $items->search($search);
        }
        if (!is_null($perPage)) {
            return ProductResource::collection($items->paginate($perPage));
        } else {
            return ProductResource::collection($items->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $credentials = $request->all();
        try {
            DB::beginTransaction();
            $obj = Product::create($credentials);
            DB::commit();
            return new ProductResource($obj);
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
        $find = Product::where('id', $id)->first();
        if (is_null($find)) {
            return $this->responseNotFound();
        }
        return new ProductResource($find);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $obj = Product::find($id);
        $credentials = $request->all();
        try {
            DB::beginTransaction();
            $obj->update($credentials);
            DB::commit();
            return new ProductResource($obj);
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
        $find = Product::find($id);
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
