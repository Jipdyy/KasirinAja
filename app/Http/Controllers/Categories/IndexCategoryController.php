<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexCategoryController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->search($request->query('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        return view('categories.index', compact('categories'));
    }
}
