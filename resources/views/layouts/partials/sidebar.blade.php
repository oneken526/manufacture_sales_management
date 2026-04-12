<ul class="space-y-0.5 px-3 py-2">

    {{-- admin: 全メニュー --}}
    @role('admin')
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                ダッシュボード
            </a>
        </li>

        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">マスタ管理</span>
        </li>
        <li>
            <a href="{{ Route::has('customers.index') ? route('customers.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                得意先管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('products.index') ? route('products.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                商品管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('warehouses.index') ? route('warehouses.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('warehouses.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                倉庫管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('users.index') ? route('users.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ユーザー管理
            </a>
        </li>

        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">販売管理</span>
        </li>
        <li>
            <a href="{{ Route::has('quotations.index') ? route('quotations.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('quotations.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                見積管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                受注管理
            </a>
        </li>

        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">製造</span>
        </li>
        <li>
            <a href="{{ Route::has('manufacture-orders.index') ? route('manufacture-orders.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('manufacture-orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                製造指示
            </a>
        </li>

        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">倉庫・出荷</span>
        </li>
        <li>
            <a href="{{ Route::has('shipments.index') ? route('shipments.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('shipments.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                出荷管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('stocks.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                在庫管理
            </a>
        </li>

        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">請求・入金</span>
        </li>
        <li>
            <a href="{{ Route::has('invoices.index') ? route('invoices.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('invoices.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
                請求管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('payments.index') ? route('payments.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('payments.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                入金管理
            </a>
        </li>
    @endrole

    {{-- sales: 見積・受注・得意先 --}}
    @role('sales')
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                ダッシュボード
            </a>
        </li>
        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">販売管理</span>
        </li>
        <li>
            <a href="{{ Route::has('customers.index') ? route('customers.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                得意先管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('quotations.index') ? route('quotations.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('quotations.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                見積管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('orders.index') ? route('orders.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                受注管理
            </a>
        </li>
    @endrole

    {{-- manufacture: 製造指示のみ --}}
    @role('manufacture')
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                ダッシュボード
            </a>
        </li>
        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">製造</span>
        </li>
        <li>
            <a href="{{ Route::has('manufacture-orders.index') ? route('manufacture-orders.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('manufacture-orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                製造指示
            </a>
        </li>
    @endrole

    {{-- warehouse: 出荷・在庫 --}}
    @role('warehouse')
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                ダッシュボード
            </a>
        </li>
        <li class="mt-5 mb-1.5 px-3">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">倉庫・出荷</span>
        </li>
        <li>
            <a href="{{ Route::has('shipments.index') ? route('shipments.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('shipments.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                出荷管理
            </a>
        </li>
        <li>
            <a href="{{ Route::has('stocks.index') ? route('stocks.index') : '#' }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors
                      {{ request()->routeIs('stocks.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                在庫管理
            </a>
        </li>
    @endrole

</ul>
