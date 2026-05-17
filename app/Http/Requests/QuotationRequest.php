<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Rule::exists() はモデルのグローバルスコープ（SoftDeletes）を適用するため、
            // 論理削除済みの得意先・商品IDが送信されても弾くことができる 🔵 セキュリティ
            'customer_id'          => ['required', 'integer', Rule::exists(Customer::class, 'id')],
            'valid_until'          => ['nullable', 'date'],
            'details'              => ['required', 'array', 'min:1'],
            'details.*.product_id' => ['required', 'integer', Rule::exists(Product::class, 'id')],
            'details.*.quantity'   => ['required', 'numeric', 'min:1'],
            'details.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'          => '得意先を選択してください',
            'customer_id.exists'            => '選択された得意先は存在しません',
            'valid_until.date'              => '有効期限を正しい日付形式で入力してください',
            'details.required'              => '明細を1行以上入力してください',
            'details.min'                   => '明細を1行以上入力してください',
            'details.*.product_id.required' => '商品を選択してください',
            'details.*.product_id.exists'   => '選択された商品は存在しません',
            'details.*.quantity.required'   => '数量を入力してください',
            'details.*.quantity.min'        => '数量は1以上の値を入力してください',
            'details.*.unit_price.required' => '単価を入力してください',
            'details.*.unit_price.min'      => '単価は0以上の値を入力してください',
        ];
    }
}
