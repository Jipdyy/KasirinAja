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

class UpdateCartItemController extends Controller
{
    public function __invoke(Request $request, Product $product, CartService $cart): RedirectResponse|JsonResponse
    {
        Gate::authorize('create', Transaction::class);

        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:0'],
        ]);

        $cart->set($product, (int) $validated['qty']);

        return $request->expectsJson()
            ? response()->json($cart->snapshot())
            : back();
    }
}