<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DestroyCartItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product, CartService $cart): RedirectResponse
    {
        Gate::authorize('create', Transaction::class);
        $cart->remove($product);
        return back();
    }
}
