<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DestroyCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CartService $cart): RedirectResponse
    {
        Gate::authorize('create', Transaction::class);
        $cart->clear();
        return back();
    }

}
