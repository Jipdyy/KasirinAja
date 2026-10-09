<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IndexProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('viewAny', Product::class);

        $products = Product::with('category')                   
            ->search($request->search)
            ->inCategory($request->category)
            ->statusIs($request->status)             
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products'   => $products,
            'categories' => Category::orderBy('name')->get(),   
        ]);
    }
}
