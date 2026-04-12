<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::withCount('products')
            ->orderBy('name')
            ->paginate(20);

        return view('product-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('product-categories.create');
    }

    public function store(ProductCategoryRequest $request): RedirectResponse
    {
        ProductCategory::create($request->validated());

        return redirect()->route('product-categories.index')
            ->with('success', 'カテゴリを登録しました。');
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('product-categories.edit', compact('productCategory'));
    }

    public function update(ProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update($request->validated());

        return redirect()->route('product-categories.index')
            ->with('success', 'カテゴリを更新しました。');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->exists()) {
            return redirect()->route('product-categories.index')
                ->with('error', 'この商品カテゴリには商品が登録されているため削除できません。');
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')
            ->with('success', 'カテゴリを削除しました。');
    }
}
