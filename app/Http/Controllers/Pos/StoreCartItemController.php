<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class StoreCartItemController extends Controller
{
    public function __invoke(Request $request, Product $product, CartService $cart): RedirectResponse|JsonResponse
    {
        Gate::authorize('create', Transaction::class);

        if (! $product->isSellable()) {
            throw ValidationException::withMessages([
                'cart' => "{$product->name} tidak tersedia.",
            ]);
        }

        $cart->add($product);

        return $request->expectsJson()
            ? response()->json($cart->snapshot())
            : back();
    }
}