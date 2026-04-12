<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'name_kana',
        'postal_code',
        'address',
        'phone',
        'email',
        'closing_day',
        'credit_limit',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'closing_day'  => 'integer',
            'credit_limit' => 'decimal:2',
        ];
    }

    public function specialPrices(): HasMany
    {
        return $this->hasMany(CustomerSpecialPrice::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /** 締日の表示テキスト（99 = 月末） */
    public function getClosingDayLabelAttribute(): string
    {
        return $this->closing_day === 99 ? '月末' : $this->closing_day . '日';
    }
}
