<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'code')->ignore($productId)->whereNull('deleted_at'),
            ],
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'unit_price'  => ['required', 'numeric', 'min:0'],
            'unit_name'   => ['nullable', 'string', 'max:20'],
            'notes'       => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code'        => '商品コード',
            'name'        => '商品名',
            'category_id' => 'カテゴリ',
            'unit_price'  => '標準単価',
            'unit_name'   => '単位',
            'notes'       => '備考',
        ];
    }
}
