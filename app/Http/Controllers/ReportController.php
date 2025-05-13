<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default date range: first day to last day of current month
        $startDate = $request->input('start_date', Carbon::now()->firstOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->lastOfMonth()->format('Y-m-d'));

        // Validate dates
        $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
        ]);

        // Query paid orders within date range
        $reports = Order::where('status', Order::STATUS_PAID)
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->selectRaw('DATE(tanggal) as tanggal_transaksi, COUNT(*) as jumlah_transaksi, SUM(subtotal + tax) as total_pendapatan')
            ->groupBy('tanggal_transaksi')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Calculate summary
        $summary = [
            'total_transaksi' => $reports->sum('jumlah_transaksi'),
            'total_pendapatan' => $reports->sum('total_pendapatan'),
        ];

        return view('reports.index', compact('reports', 'summary', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->firstOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->lastOfMonth()->format('Y-m-d'));

        $reports = Order::where('status', Order::STATUS_PAID)
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->selectRaw('DATE(tanggal) as tanggal_transaksi, COUNT(*) as jumlah_transaksi, SUM(subtotal + tax) as total_pendapatan')
            ->groupBy('tanggal_transaksi')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $summary = [
            'total_transaksi' => $reports->sum('jumlah_transaksi'),
            'total_pendapatan' => $reports->sum('total_pendapatan'),
        ];

        $printDate = now()->format('d/m/Y H:i');
        $period = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        return view('reports.print', compact('reports', 'summary', 'startDate', 'endDate', 'printDate', 'period'));
    }
}