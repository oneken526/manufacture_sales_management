<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// 🔵 タスク仕様書・要件定義 REQ-020 より
class UnitPriceController extends Controller
{
    /**
     * 得意先の適用単価を返す（特別単価 or 標準単価）。
     * 見積・受注の明細行で商品選択時に Ajax で呼び出される（REQ-020）。
     */
    public function show(Customer $customer, Request $request): JsonResponse
    {
        $product = Product::findOrFail($request->input('product_id'));

        // 特別単価を検索（ソフトデリート済みは除外）
        $special = $customer->specialPrices()
            ->where('product_id', $product->id)
            ->first();

        return response()->json([
            'unit_price' => $special ? (float) $special->unit_price : (float) $product->unit_price,
            'is_special'  => (bool) $special,
        ]);
    }
}
