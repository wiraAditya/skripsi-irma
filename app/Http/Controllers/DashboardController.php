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

        // Monthly Income (net after refunds)
        $monthlyIncome = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->withSum('refunds', 'refund_amount')
            ->get()
            ->sum(function($order) {
                return ($order->subtotal+$order->tax) - $order->refunds_sum_refund_amount;
            });

        // Total Orders (excluding canceled orders)
        $totalOrders = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('status',  [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->count();

        // Daily Income (net after refunds)
        $dailyIncome = DB::table('orders')
            ->leftJoin('refunds', function($join) {
                $join->on('orders.id', '=', 'refunds.order_id');
            })
            ->whereBetween('orders.tanggal', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(orders.tanggal) as date'),
                DB::raw('SUM(orders.subtotal + orders.tax) as gross_total'),
                DB::raw('COALESCE(SUM(refunds.refund_amount), 0) as total_refunds')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'total' => $item->gross_total - $item->total_refunds
                ];
            })
            ->toArray();

        // Best Selling Menus (net after refunds)
        $bestSellingMenus = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menu', 'order_details.menu_id', '=', 'menu.id')
            ->leftJoin('refund_items', 'order_details.id', '=', 'refund_items.order_detail_id')
            ->leftJoin('refunds', function($join) {
                $join->on('refund_items.refund_id', '=', 'refunds.id');
            })
            ->whereBetween('orders.tanggal', [$startDate, $endDate])
            ->whereIn('orders.status', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->select(
                'menu.nama as name',
                DB::raw('SUM(order_details.qty) as gross_qty'),
                DB::raw('COALESCE(SUM(refund_items.quantity), 0) as refunded_qty'),
                DB::raw('(SUM(order_details.qty) - COALESCE(SUM(refund_items.quantity), 0)) as net_qty'),
                DB::raw('SUM(order_details.qty * order_details.harga) as gross_amount'),
                DB::raw('COALESCE(SUM(refund_items.refund_amount), 0) as refunded_amount'),
                DB::raw('(SUM(order_details.qty * order_details.harga) - COALESCE(SUM(refund_items.refund_amount), 0)) as net_amount')
            )
            ->groupBy('menu.id', 'menu.nama')
            ->orderByDesc('net_qty')
            ->limit(5)
            ->get()
            ->toArray();


        // Table Usage (excluding canceled orders)
        $tableUsage = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->join('meja', 'orders.meja_id', '=', 'meja.id')
            ->whereIn('orders.status',  [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->select('meja.nama as meja', DB::raw('COUNT(*) as count'))
            ->groupBy('meja.id')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        // Most Occupied Table (excluding canceled orders)
        $mostOccupiedTable = Order::whereBetween('tanggal', [$startDate, $endDate])
            ->join('meja', 'orders.meja_id', '=', 'meja.id')
            ->whereIn('orders.status',  [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
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