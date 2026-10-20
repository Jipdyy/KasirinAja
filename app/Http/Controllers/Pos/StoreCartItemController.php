<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoreCartItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Product $product, CartService $cart): RedirectResponse
    {
        Gate::authorize('create', Transaction::class);

        if (! $product->isSellAble()) {
            return back()->with('error', "{$product->name} tidak bisa dijual (nonaktif atau stok habis).");
        }

        $cart->add($product);
        
        return back();
    }
}
