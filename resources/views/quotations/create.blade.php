@extends('layouts.app')

@section('title', '見積書登録')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">見積書登録</h1>
    <p class="text-sm text-slate-500 mt-0.5">新しい見積書を作成します</p>
</div>

<form method="POST" action="{{ route('quotations.store') }}" id="quotation-form">
    @csrf

    {{-- 基本情報 --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-5">
        <h2 class="text-base font-semibold text-slate-700 mb-4">基本情報</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- 得意先 --}}
            <div>
                <x-inputs.input-label value="得意先" :required="true" />
                <x-inputs.select name="customer_id" id="customer_id" class="w-full">
                    <option value="">-- 選択してください --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            @selected(old('customer_id') == $customer->id)>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </x-inputs.select>
                <x-inputs.input-error :messages="$errors->get('customer_id')" />
            </div>

            {{-- 有効期限 --}}
            <div>
                <x-inputs.input-label value="有効期限" />
                <x-inputs.text-input
                    type="date"
                    name="valid_until"
                    value="{{ old('valid_until') }}"
                    class="w-full" />
                <x-inputs.input-error :messages="$errors->get('valid_until')" />
            </div>

            {{-- 作成者（表示のみ） --}}
            <div>
                <x-inputs.input-label value="作成者" />
                <p class="px-3 py-2 text-sm text-slate-700 border border-slate-200 rounded-lg bg-slate-50">
                    {{ auth()->user()->name }}
                </p>
            </div>
        </div>
    </div>

    {{-- 明細 --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-5">
        <h2 class="text-base font-semibold text-slate-700 mb-4">明細</h2>

        <x-inputs.input-error :messages="$errors->get('details')" class="mb-3" />

        <table class="w-full text-sm mb-3">
            <thead>
                <tr class="bg-slate-50 text-slate-600">
                    <th class="px-3 py-2 text-left font-medium w-2/5">商品</th>
                    <th class="px-3 py-2 text-right font-medium w-24">数量</th>
                    <th class="px-3 py-2 text-right font-medium w-32">単価（円）</th>
                    <th class="px-3 py-2 text-right font-medium w-32">金額（円）</th>
                    <th class="px-3 py-2 w-12"></th>
                </tr>
            </thead>
            <tbody id="detail-rows">
                @foreach(old('details', [[]]) as $i => $detail)
                @php
                    $nameProduct  = 'details[' . $i . '][product_id]';
                    $nameQuantity = 'details[' . $i . '][quantity]';
                    $namePrice    = 'details[' . $i . '][unit_price]';
                    $errProduct   = $errors->get('details.' . $i . '.product_id');
                    $errQuantity  = $errors->get('details.' . $i . '.quantity');
                    $errPrice     = $errors->get('details.' . $i . '.unit_price');
                @endphp
                <tr class="detail-row border-t border-slate-100">
                    <td class="px-3 py-2">
                        <select name="{{ $nameProduct }}"
                            class="product-select border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent w-full {{ $errProduct ? 'border-red-400' : '' }}">
                            <option value="">-- 選択 --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    @selected(($detail['product_id'] ?? null) == $product->id)>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-inputs.input-error :messages="$errProduct" />
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" name="{{ $nameQuantity }}"
                            value="{{ $detail['quantity'] ?? '' }}"
                            min="1" step="0.001"
                            class="quantity border {{ $errQuantity ? 'border-red-400' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent w-full text-right" />
                        <x-inputs.input-error :messages="$errQuantity" />
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" name="{{ $namePrice }}"
                            value="{{ $detail['unit_price'] ?? '' }}"
                            min="0" step="1"
                            class="unit-price border {{ $errPrice ? 'border-red-400' : 'border-slate-300' }} rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent w-full text-right" />
                        <x-inputs.input-error :messages="$errPrice" />
                    </td>
                    <td class="px-3 py-2 text-right">
                        <span class="row-amount text-slate-700">—</span>
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button type="button"
                            class="remove-row text-slate-400 hover:text-red-500 transition-colors"
                            title="行を削除">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" id="add-row"
            class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            行を追加
        </button>
    </div>

    {{-- 合計金額 --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6 flex justify-end">
        <div class="text-right">
            <span class="text-sm text-slate-500 mr-4">合計金額（税抜）</span>
            <span id="total-amount" class="text-xl font-bold text-slate-800">0 円</span>
        </div>
    </div>

    {{-- ボタン --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('quotations.index', [], false) }}"
            class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
            キャンセル
        </a>
        <button type="submit"
            class="inline-flex items-center px-5 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
            保存する
        </button>
    </div>
</form>

{{-- 行テンプレート（JavaScript で複製して使う） --}}
<template id="row-template">
    <tr class="detail-row border-t border-slate-100">
        <td class="px-3 py-2">
            <select name="details[__INDEX__][product_id]"
                class="product-select border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent w-full">
                <option value="">-- 選択 --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="details[__INDEX__][quantity]"
                min="1" step="0.001" value=""
                class="quantity border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent w-full text-right" />
        </td>
        <td class="px-3 py-2">
            <input type="number" name="details[__INDEX__][unit_price]"
                min="0" step="1" value=""
                class="unit-price border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent w-full text-right" />
        </td>
        <td class="px-3 py-2 text-right">
            <span class="row-amount text-slate-700">—</span>
        </td>
        <td class="px-3 py-2 text-center">
            <button type="button"
                class="remove-row text-slate-400 hover:text-red-500 transition-colors"
                title="行を削除">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
(function () {
    const tbody      = document.getElementById('detail-rows');
    const addBtn     = document.getElementById('add-row');
    const totalEl    = document.getElementById('total-amount');
    const customerEl = document.getElementById('customer_id');
    const template   = document.getElementById('row-template');

    // -------------------------------------------------------
    // 行追加
    // -------------------------------------------------------
    addBtn.addEventListener('click', () => {
        const index  = tbody.querySelectorAll('.detail-row').length;
        const html   = template.innerHTML.replaceAll('__INDEX__', index);
        const tmp    = document.createElement('tbody');
        tmp.innerHTML = html;
        const row    = tmp.firstElementChild;
        tbody.appendChild(row);
        bindRow(row);
        updateRemoveButtons();
    });

    // -------------------------------------------------------
    // 行イベントバインド
    // -------------------------------------------------------
    function bindRow(row) {
        row.querySelector('.remove-row').addEventListener('click', () => {
            row.remove();
            reindexRows();
            recalcTotal();
            updateRemoveButtons();
        });
        row.querySelector('.quantity').addEventListener('input',    () => { recalcRow(row); recalcTotal(); });
        row.querySelector('.unit-price').addEventListener('input',  () => { recalcRow(row); recalcTotal(); });
        row.querySelector('.product-select').addEventListener('change', () => fetchUnitPrice(row));
    }

    // -------------------------------------------------------
    // 削除ボタン有効/無効（1行のみの場合は無効）
    // -------------------------------------------------------
    function updateRemoveButtons() {
        const rows    = tbody.querySelectorAll('.detail-row');
        const disable = rows.length <= 1;
        rows.forEach(r => {
            const btn = r.querySelector('.remove-row');
            btn.disabled = disable;
            btn.classList.toggle('opacity-30', disable);
            btn.classList.toggle('cursor-not-allowed', disable);
        });
    }

    // -------------------------------------------------------
    // 行削除後に name の index を振り直す
    // -------------------------------------------------------
    function reindexRows() {
        tbody.querySelectorAll('.detail-row').forEach((row, i) => {
            row.querySelector('.product-select').name = `details[${i}][product_id]`;
            row.querySelector('.quantity').name       = `details[${i}][quantity]`;
            row.querySelector('.unit-price').name     = `details[${i}][unit_price]`;
        });
    }

    // -------------------------------------------------------
    // 行の小計計算
    // -------------------------------------------------------
    function recalcRow(row) {
        const qty   = parseFloat(row.querySelector('.quantity').value)   || 0;
        const price = parseFloat(row.querySelector('.unit-price').value) || 0;
        const amt   = qty * price;
        row.querySelector('.row-amount').textContent =
            amt > 0 ? amt.toLocaleString('ja-JP') + ' 円' : '—';
        row.dataset.amount = amt;
    }

    // -------------------------------------------------------
    // 合計金額計算
    // -------------------------------------------------------
    function recalcTotal() {
        let total = 0;
        tbody.querySelectorAll('.detail-row').forEach(r => {
            total += parseFloat(r.dataset.amount || 0);
        });
        totalEl.textContent = total.toLocaleString('ja-JP') + ' 円';
    }

    // -------------------------------------------------------
    // 単価の Ajax 取得（REQ-020）
    // -------------------------------------------------------
    function fetchUnitPrice(row) {
        const cid = customerEl.value;
        const pid = row.querySelector('.product-select').value;
        if (!cid || !pid) return;

        fetch(`/api/customers/${cid}/unit-price?product_id=${pid}`)
            .then(r => r.json())
            .then(data => {
                row.querySelector('.unit-price').value = data.unit_price;
                recalcRow(row);
                recalcTotal();
            });
    }

    // 得意先変更時に全行の単価を再取得（REQ-020）
    customerEl.addEventListener('change', () => {
        tbody.querySelectorAll('.detail-row').forEach(fetchUnitPrice);
    });

    // -------------------------------------------------------
    // 初期バインド
    // -------------------------------------------------------
    tbody.querySelectorAll('.detail-row').forEach(row => {
        bindRow(row);
        recalcRow(row);
    });
    recalcTotal();
    updateRemoveButtons();
})();
</script>
@endpush
