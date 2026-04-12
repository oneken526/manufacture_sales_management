<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('product_category')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('product_categories', 'name')->ignore($categoryId)->whereNull('deleted_at'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'カテゴリ名',
        ];
    }
}
