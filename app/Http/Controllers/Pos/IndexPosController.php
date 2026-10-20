<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class IndexPosController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CartService $cart): View
    {
        Gate::authorize('create', Transaction::class);

        $products = Product::query()
            ->sellAble()
            ->search($request->query('search'))
            ->inCategory($request->query('category'))
            ->with('category')
            ->orderBy('name')
            ->paginate(24)
            ->withQuerystring();
        
        $categories = Category::orderBy('name')->get();

        return view('pos.index', [
            'products' => $products,
            'categories' => $categories,
            'lines' => $cart->lines(),
            'totalItems' => $cart->totalItems(),
            'totalAmount' => $cart->totalAmount(),
            'hasIssues' => $cart->hasIssues(),   
        ]);
    }
}
