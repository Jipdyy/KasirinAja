<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;

class UpdateCategoryController extends Controller
{
    public function __invoke(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diubah.');
        // TODO: dua baris inti.
        // 1. Update kategori dengan data yang sudah tervalidasi.
        //    Ingat pola StoreCategoryController — method apa di Eloquent
        //    yang menerima array hasil validate() langsung, tanpa perlu
        //    menyebut tiap kolom satu-satu?
        //
        // 2. Redirect kembali ke mana? Ingat pola yang sudah dipakai
        //    di controller lain (Store, kalau sudah ada contohnya) —
        //    konsisten pakai redirect()->route(...) dengan flash message
        //    apa, supaya nanti <x-ui.flash-message /> (yang masih
        //    belum dibuat) punya sesuatu untuk ditampilkan.
    }
}
