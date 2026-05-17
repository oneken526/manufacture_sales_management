<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// 🔵 REQ-013・REQ-014・REQ-020: 見積書の一覧・登録・編集を担当するコントローラー

class QuotationController extends Controller
{
    // 🔵 REQ-013: admin / sales ロールのみ一覧表示可能
    public function index(Request $request): View
    {
        $query = Quotation::with(['customer', 'user']);

        // 見積番号の部分一致検索
        if ($number = $request->input('quotation_number')) {
            $query->where('quotation_number', 'like', "%{$number}%");
        }

        // 得意先名の部分一致検索（リレーション経由）
        if ($customerName = $request->input('customer_name')) {
            $query->whereHas('customer', fn ($q) => $q->where('name', 'like', "%{$customerName}%"));
        }

        // ステータスの完全一致
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 作成日の範囲絞り込み
        if ($from = $request->input('created_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('created_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // デフォルトは作成日の降順 🔵 仕様書 §3
        $quotations = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('quotations.index', compact('quotations'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();

        return view('quotations.create', compact('customers', 'products'));
    }

    public function store(QuotationRequest $request): RedirectResponse
    {
        $quotation = Quotation::create([
            'quotation_number' => Quotation::generateNextNumber(),
            'customer_id'      => $request->customer_id,
            'user_id'          => auth()->id(),
            'valid_until'      => $request->valid_until,
            'status'           => 'draft',
        ]);

        $quotation->saveDetails($request->details);

        return redirect()->route('quotations.show', $quotation)
            ->with('success', '見積書を作成しました');
    }
}
