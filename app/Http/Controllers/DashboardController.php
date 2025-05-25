<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default values
        $month = (int)$request->input('month', now()->month);
        $year = (int)$request->input('year', now()->year);
        
        $startDate = now()->year($year)->month($month)->startOfMonth();
        $endDate = now()->year($year)->month($month)->endOfMonth();

        // Monthly Income
        $monthlyIncome = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->sum('subtotal');

        // Total Orders
        $totalOrders = Order::whereBetween('tanggal', [$startDate, $endDate])->count();

        // Daily Income
        $dailyIncome = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('DATE(tanggal) as date, SUM(subtotal) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        // Best Selling Menus
        $bestSellingMenus = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menu', 'order_details.menu_id', '=', 'menu.id')
            ->whereBetween('orders.tanggal', [$startDate, $endDate])
            ->select('menu.nama as name', DB::raw('SUM(order_details.qty) as qty'))
            ->groupBy('menu.id')
            ->orderByDesc('qty')
            ->limit(5)
            ->get()
            ->toArray();

        // Table Usage
        $tableUsage = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->join('meja', 'orders.meja_id', '=', 'meja.id')
            ->select('meja.nama as meja', DB::raw('COUNT(*) as count'))
            ->groupBy('meja.id')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        // Most Occupied Table
        $mostOccupiedTable = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->join('meja', 'orders.meja_id', '=', 'meja.id')
            ->select('meja.nama')
            ->groupBy('meja.id')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->first();

        

        return view('dashboard', compact(
            'monthlyIncome',
            'totalOrders',
            'dailyIncome',
            'bestSellingMenus',
            'tableUsage',
            'mostOccupiedTable',
            'month',
            'year'
        ));
    }
}