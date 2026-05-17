<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'quotation_number',
        'customer_id',
        'user_id',
        'valid_until',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'valid_until'  => 'date',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(QuotationDetail::class)->orderBy('sort_order');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * 見積番号を採番して返す 🔵 仕様書 §4
     *
     * 形式: Q{YYYYMM}-{3桁連番}（例: Q202605-001）
     * 同月内の最大連番+1を使用。論理削除済みも含めて採番するため欠番は生じない。
     */
    public static function generateNextNumber(): string
    {
        $prefix = 'Q' . now()->format('Ym');

        $last = static::withTrashed()
            ->where('quotation_number', 'like', "{$prefix}-%")
            ->orderByDesc('quotation_number')
            ->value('quotation_number');

        $seq = $last ? (int) substr($last, -3) + 1 : 1;

        return sprintf('%s-%03d', $prefix, $seq);
    }

    /**
     * 明細行を一括保存する 🔵 仕様書 §5
     *
     * amount（金額）は quantity × unit_price で自動計算。
     * sort_order はフォームの行順（配列インデックス）をそのまま使用。
     *
     * @param array<int, array{product_id: int, quantity: numeric, unit_price: numeric}> $details
     */
    public function saveDetails(array $details): void
    {
        foreach ($details as $index => $detail) {
            $this->details()->create([
                'product_id' => $detail['product_id'],
                'quantity'   => $detail['quantity'],
                'unit_price' => $detail['unit_price'],
                'amount'     => (float) $detail['quantity'] * (float) $detail['unit_price'],
                'sort_order' => $index,
            ]);
        }
    }

    /** ステータスバッジの表示設定（variant・label）を返す 🔵 仕様書 §5 */
    public function statusBadge(): array
    {
        return match($this->status) {
            'draft'    => ['variant' => 'default', 'label' => '下書き'],
            'pending'  => ['variant' => 'warning', 'label' => '承認待ち'],
            'approved' => ['variant' => 'success', 'label' => '承認済み'],
            'rejected' => ['variant' => 'danger',  'label' => '却下'],
            default    => ['variant' => 'default', 'label' => $this->status],
        };
    }
}
