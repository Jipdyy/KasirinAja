<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $products = Product::with('category')
            ->when($request->search, fn ($query, $search) =>
                $query->where('name', 'like', "%{$search}%")
            )
            ->when($request->category_id, fn ($query, $categoryId) =>
                $query->where('category_id', $categoryId)
            )
            // TODO: filter status Tersedia/Habis.
            // Pertanyaan pemandu: lihat lagi scope `sellable`/`statusIs`
            // yang pernah dibahas di kerangka model Product — ini saat
            // yang tepat memakainya. Request query param-nya apa,
            // dan bagaimana `when()` memanggil scope itu?
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }
}
