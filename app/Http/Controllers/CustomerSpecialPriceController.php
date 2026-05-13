<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSpecialPrice;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

// 🔵 タスク完了条件・Webルート仕様より
class CustomerSpecialPriceController extends Controller
{
    /** 得意先の特別単価一覧を表示する */
    public function index(Customer $customer): View
    {
        $specialPrices = $customer->specialPrices()
            ->with('product')
            ->orderBy('id')
            ->get();

        // 未登録商品の選択肢（登録済みを除外）
        $registeredProductIds = $specialPrices->pluck('product_id');
        $products = Product::whereNotIn('id', $registeredProductIds)
            ->orderBy('code')
            ->get();

        return view('customers.special-prices.index', compact('customer', 'specialPrices', 'products'));
    }

    /** 特別単価を登録する */
    public function store(Request $request, Customer $customer): RedirectResponse
    {
        // 🔵 タスク仕様書 store() バリデーションより
        $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
                // 同一得意先・同一商品の重複登録防止（ソフトデリート済みを除く）
                Rule::unique('customer_special_prices')->where(
                    fn ($q) => $q->where('customer_id', $customer->id)->whereNull('deleted_at')
                ),
            ],
            'unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $customer->specialPrices()->create($request->only('product_id', 'unit_price'));

        return redirect()->route('customers.special-prices.index', $customer)
            ->with('success', '特別単価を登録しました。');
    }

    /** 特別単価を更新する */
    public function update(Request $request, Customer $customer, CustomerSpecialPrice $specialPrice): RedirectResponse
    {
        // 🔵 タスク完了条件「PUT /customers/{id}/special-prices/{spId}」より
        $request->validate([
            'unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $specialPrice->update($request->only('unit_price'));

        return redirect()->route('customers.special-prices.index', $customer)
            ->with('success', '特別単価を更新しました。');
    }

    /** 特別単価をソフトデリートする */
    public function destroy(Customer $customer, CustomerSpecialPrice $specialPrice): RedirectResponse
    {
        // 🔵 タスク完了条件「DELETE /customers/{id}/special-prices/{spId}」より
        $specialPrice->delete();

        return redirect()->route('customers.special-prices.index', $customer)
            ->with('success', '特別単価を削除しました。');
    }
}
