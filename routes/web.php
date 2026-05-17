<?php

use App\Http\Controllers\Api\UnitPriceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerSpecialPriceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin|sales'])->group(function (): void {
        Route::resource('customers', CustomerController::class);
        Route::resource('customers.special-prices', CustomerSpecialPriceController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('products', ProductController::class);
        // 🔵 REQ-013・REQ-015: 見積書一覧・詳細（TASK-0014）
        Route::resource('quotations', QuotationController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);
        // 複製は /copy エンドポイント（TASK-0016 で実装予定）🔵 仕様書 §6
        Route::post('quotations/{quotation}/copy', [QuotationController::class, 'copy'])
            ->name('quotations.copy');
        // 🔵 Ajax 単価取得（REQ-020）: 見積・受注明細で商品選択時に呼び出す
        Route::get('/api/customers/{customer}/unit-price', [UnitPriceController::class, 'show'])
            ->name('api.customers.unit-price');
    });

    Route::middleware(['role:admin'])->group(function (): void {
        Route::resource('warehouses', WarehouseController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Ajax 商品検索（全認証ユーザーが利用可能）
    Route::get('/api/products/search', [ProductController::class, 'search'])->name('api.products.search');
});

// UI カタログ（ローカル環境のみ）
if (app()->environment('local')) {
    Route::get('/ui-catalog', function () {
        return view('ui-catalog');
    })->name('ui-catalog');
}

require __DIR__.'/auth.php';
