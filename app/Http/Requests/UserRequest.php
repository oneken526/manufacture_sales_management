<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at')],
            'role'  => ['required', Rule::in(['admin', 'sales', 'manufacture', 'warehouse'])],
        ];

        if ($this->isMethod('POST')) {
            $rules['password']              = ['required', 'string', 'min:8', 'confirmed'];
            $rules['password_confirmation'] = ['required'];
        } else {
            $rules['password']              = ['nullable', 'string', 'min:8', 'confirmed'];
            $rules['password_confirmation'] = ['nullable'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name'                  => '名前',
            'email'                 => 'メールアドレス',
            'password'              => 'パスワード',
            'password_confirmation' => 'パスワード（確認）',
            'role'                  => 'ロール',
        ];
    }
}
