<ul class="space-y-1 px-2">

    {{-- admin: 全メニュー --}}
    @role('admin')
        <li>
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700' }}">
                ダッシュボード
            </a>
        </li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">マスタ管理</li>
        <li><a href="{{ Route::has('customers.index') ? route('customers.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">得意先管理</a></li>
        <li><a href="{{ Route::has('products.index') ? route('products.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">商品管理</a></li>
        <li><a href="{{ Route::has('warehouses.index') ? route('warehouses.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">倉庫管理</a></li>
        <li><a href="{{ Route::has('users.index') ? route('users.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">ユーザー管理</a></li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">販売管理</li>
        <li><a href="{{ Route::has('quotations.index') ? route('quotations.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">見積管理</a></li>
        <li><a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">受注管理</a></li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">製造</li>
        <li><a href="{{ Route::has('manufacture-orders.index') ? route('manufacture-orders.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">製造指示</a></li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">倉庫・出荷</li>
        <li><a href="{{ Route::has('shipments.index') ? route('shipments.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">出荷管理</a></li>
        <li><a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">在庫管理</a></li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">請求・入金</li>
        <li><a href="{{ Route::has('invoices.index') ? route('invoices.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">請求管理</a></li>
        <li><a href="{{ Route::has('payments.index') ? route('payments.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">入金管理</a></li>
    @endrole

    {{-- sales: 見積・受注・得意先・レポート --}}
    @role('sales')
        <li>
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700' }}">
                ダッシュボード
            </a>
        </li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">販売管理</li>
        <li><a href="{{ Route::has('customers.index') ? route('customers.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">得意先管理</a></li>
        <li><a href="{{ Route::has('quotations.index') ? route('quotations.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">見積管理</a></li>
        <li><a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">受注管理</a></li>
    @endrole

    {{-- manufacture: 製造指示のみ --}}
    @role('manufacture')
        <li>
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700' }}">
                ダッシュボード
            </a>
        </li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">製造</li>
        <li><a href="{{ Route::has('manufacture-orders.index') ? route('manufacture-orders.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">製造指示</a></li>
    @endrole

    {{-- warehouse: 出荷・在庫 --}}
    @role('warehouse')
        <li>
            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700' }}">
                ダッシュボード
            </a>
        </li>
        <li class="mt-3 mb-1 text-xs text-gray-400 px-4 uppercase tracking-wider">倉庫・出荷</li>
        <li><a href="{{ Route::has('shipments.index') ? route('shipments.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">出荷管理</a></li>
        <li><a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}" class="block px-4 py-2 rounded hover:bg-gray-100 text-gray-700">在庫管理</a></li>
    @endrole

</ul>
