<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $isCreate = $this->isMethod('POST');

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at'),
            ],
            'role'  => ['required', 'string', Rule::in(['admin', 'sales', 'manufacture', 'warehouse'])],
        ];

        if ($isCreate) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        } else {
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name'     => 'ユーザー名',
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
            'role'     => 'ロール',
        ];
    }
}
