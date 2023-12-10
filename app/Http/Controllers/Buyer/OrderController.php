<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Traits\ApiTrait;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
        $items = Transaction::with(['product','user'])->byUser(auth()->user()->id)->oldest();
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

}
