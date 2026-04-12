<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'code'         => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'code')->ignore($customerId)->whereNull('deleted_at'),
            ],
            'name'         => ['required', 'string', 'max:255'],
            'name_kana'    => ['nullable', 'string', 'max:255'],
            'postal_code'  => ['nullable', 'string', 'max:10'],
            'address'      => ['nullable', 'string'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'max:255'],
            'closing_day'  => ['required', 'integer', 'in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,99'],
            'credit_limit' => ['required', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code'         => '得意先コード',
            'name'         => '得意先名',
            'name_kana'    => '得意先名フリガナ',
            'postal_code'  => '郵便番号',
            'address'      => '住所',
            'phone'        => '電話番号',
            'email'        => 'メールアドレス',
            'closing_day'  => '締日',
            'credit_limit' => '与信限度額',
            'notes'        => '備考',
        ];
    }
}
