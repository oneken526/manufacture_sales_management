<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * ダッシュボードを表示する
     * ログインユーザーのロールに応じてビューを切り替える
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return match (true) {
            $user->hasRole('admin')       => view('dashboard.admin'),
            $user->hasRole('sales')       => view('dashboard.sales'),
            $user->hasRole('manufacture') => view('dashboard.manufacture'),
            $user->hasRole('warehouse')   => view('dashboard.warehouse'),
            default                       => view('dashboard.admin'),
        };
    }
}
