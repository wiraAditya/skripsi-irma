<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('report_type') ?? 'recap';
        if($reportType === 'transaction') {
            return redirect()->route('reports.index.daily');
        }
        $reportType = '';
        // Default date range: first day to last day of current month
        $startDate = $request->input('start_date', Carbon::now()->firstOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->lastOfMonth()->format('Y-m-d'));
        $today = Carbon::now()->format('Y-m-d');

        // Validate dates
        $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
        ]);

        // First get the refund sums per order
        $refundSums = DB::table('refunds')
            ->select('order_id', DB::raw('SUM(refund_amount) as total_refund'))
            ->groupBy('order_id');

        // Then join with orders and calculate the reports
        $reports = DB::table('orders')
            ->leftJoinSub($refundSums, 'refund_totals', function ($join) {
                $join->on('orders.id', '=', 'refund_totals.order_id');
            })
            ->select(
                DB::raw('DATE(tanggal) as tanggal_transaksi'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(subtotal + tax) as total_pendapatan_kotor'),
                DB::raw('SUM((subtotal + tax) - IFNULL(refund_totals.total_refund, 0)) as total_pendapatan_bersih'),
                DB::raw('SUM(CASE WHEN payment_method = "'.Order::PAYMENT_CASH.'" THEN (subtotal + tax) - IFNULL(refund_totals.total_refund, 0) ELSE 0 END) as total_cash'),
                DB::raw('SUM(CASE WHEN payment_method = "'.Order::PAYMENT_DIGITAL.'" THEN (subtotal + tax) - IFNULL(refund_totals.total_refund, 0) ELSE 0 END) as total_digital'),
                DB::raw('SUM(IFNULL(refund_totals.total_refund, 0)) as total_refund')
            )
            ->whereIn('status',  [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->groupBy('tanggal_transaksi')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Calculate summary
        $summary = [
            'total_transaksi' => $reports->sum('jumlah_transaksi'),
            'total_pendapatan_kotor' => $reports->sum('total_pendapatan_kotor'),
            'total_pendapatan_bersih' => $reports->sum('total_pendapatan_bersih'),
            'total_cash' => $reports->sum('total_cash'),
            'total_digital' => $reports->sum('total_digital'),
            'total_refund' => $reports->sum('total_refund'),
        ];

        return view('reports.index', compact('reports', 'summary', 'startDate', 'endDate', 'today', 'reportType'));
    }


    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // First get the refund sums per order
        $refundSums = DB::table('refunds')
            ->select('order_id', DB::raw('SUM(refund_amount) as total_refund'))
            ->groupBy('order_id');

        // Then join with orders and calculate the reports
        $reports = DB::table('orders')
            ->leftJoinSub($refundSums, 'refund_totals', function ($join) {
                $join->on('orders.id', '=', 'refund_totals.order_id');
            })
            ->select(
                DB::raw('DATE(tanggal) as tanggal_transaksi'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(subtotal + tax) as total_pendapatan_kotor'),
                DB::raw('SUM((subtotal + tax) - IFNULL(refund_totals.total_refund, 0)) as total_pendapatan_bersih'),
                DB::raw('SUM(CASE WHEN payment_method = "'.Order::PAYMENT_CASH.'" THEN (subtotal + tax) - IFNULL(refund_totals.total_refund, 0) ELSE 0 END) as total_cash'),
                DB::raw('SUM(CASE WHEN payment_method = "'.Order::PAYMENT_DIGITAL.'" THEN (subtotal + tax) - IFNULL(refund_totals.total_refund, 0) ELSE 0 END) as total_digital'),
                DB::raw('SUM(IFNULL(refund_totals.total_refund, 0)) as total_refund')
            )
            ->whereIn('status',  [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->groupBy('tanggal_transaksi')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $summary = [
            'total_transaksi' => $reports->sum('jumlah_transaksi'),
            'total_pendapatan_kotor' => $reports->sum('total_pendapatan_kotor'),
            'total_pendapatan_bersih' => $reports->sum('total_pendapatan_bersih'),
            'total_cash' => $reports->sum('total_cash'),
            'total_digital' => $reports->sum('total_digital'),
            'total_refund' => $reports->sum('total_refund'),
        ];

        $printDate = now()->format('d/m/Y H:i');
        $period = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        return view('reports.print', compact('reports', 'summary', 'startDate', 'endDate', 'printDate', 'period'));
    }

    public function index_daily(Request $request)
    {
        $reportType = $request->input('report_type') ?? 'transaction';
        if($reportType === 'recap') {
            return redirect()->route('reports.index');
        }
        $startDate = $request->input('start_date', Carbon::now()->firstOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->lastOfMonth()->format('Y-m-d'));
    
        // Validate dates
        $request->validate([
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date',
        ]);

        // First get the refund sums per order
        $refundSums = DB::table('refunds')
            ->select('order_id', DB::raw('SUM(refund_amount) as total_refund'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('order_id');

        // Get orders with their refund totals
        $reports = DB::table('orders')
            ->leftJoinSub($refundSums, 'refund_totals', function ($join) {
                $join->on('orders.id', '=', 'refund_totals.order_id');
            })
            ->select(
                'orders.*',
                DB::raw('IFNULL(refund_totals.total_refund, 0) as total_refund')
            )
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->get();

        $totalPendapatanKotor = 0;
        $totalPendapatanBersih = 0;
        $totalCash = 0;
        $totalDigital = 0;
        $totalRefund = 0;

        foreach ($reports as $report) {
            $refundAmount = $report->total_refund ?? 0;
            $orderTotal = $report->subtotal + $report->tax;
            $netAmount = $orderTotal - $refundAmount;

            $totalPendapatanKotor += $orderTotal;
            $totalPendapatanBersih += $netAmount;
            $totalRefund += $refundAmount;

            if ($report->payment_method === Order::PAYMENT_CASH) {
                $totalCash += $netAmount;
            } elseif ($report->payment_method === Order::PAYMENT_DIGITAL) {
                $totalDigital += $netAmount;
            }
        }

        $summary = [
            'total_penjualan' => $reports->count(),
            'total_pendapatan_kotor' => $totalPendapatanKotor,
            'total_pendapatan_bersih' => $totalPendapatanBersih,
            'total_cash' => $totalCash,
            'total_digital' => $totalDigital,
            'total_refund' => $totalRefund,
        ];

        $paymentMethodLabels = [
            Order::PAYMENT_CASH => 'Tunai',
            Order::PAYMENT_DIGITAL => 'Digital'
        ];
        $orderModel = Order::class;
        return view('reports-daily.index', compact('reports', 'summary',  'paymentMethodLabels', 'orderModel', 'reportType', 'startDate', 'endDate'));
    }

    public function print_daily(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->firstOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->lastOfMonth()->format('Y-m-d'));
        // Validate dates
        $request->validate([
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date',
        ]);

        // First get the refund sums per order
        $refundSums = DB::table('refunds')
            ->select('order_id', DB::raw('SUM(refund_amount) as total_refund'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('order_id');

        // Get orders with their refund totals
        $reports = DB::table('orders')
            ->leftJoinSub($refundSums, 'refund_totals', function ($join) {
                $join->on('orders.id', '=', 'refund_totals.order_id');
            })
            ->select(
                'orders.*',
                DB::raw('IFNULL(refund_totals.total_refund, 0) as total_refund')
            )
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->get();

        $totalPendapatanKotor = 0;
        $totalPendapatanBersih = 0;
        $totalCash = 0;
        $totalDigital = 0;
        $totalRefund = 0;

        foreach ($reports as $report) {
            $refundAmount = $report->total_refund ?? 0;
            $orderTotal = $report->subtotal + $report->tax;
            $netAmount = $orderTotal - $refundAmount;

            $totalPendapatanKotor += $orderTotal;
            $totalPendapatanBersih += $netAmount;
            $totalRefund += $refundAmount;

            if ($report->payment_method === Order::PAYMENT_CASH) {
                $totalCash += $netAmount;
            } elseif ($report->payment_method === Order::PAYMENT_DIGITAL) {
                $totalDigital += $netAmount;
            }
        }

        $summary = [
            'total_penjualan' => $reports->count(),
            'total_pendapatan_kotor' => $totalPendapatanKotor,
            'total_pendapatan_bersih' => $totalPendapatanBersih,
            'total_cash' => $totalCash,
            'total_digital' => $totalDigital,
            'total_refund' => $totalRefund,
        ];

        $paymentMethodLabels = [
            Order::PAYMENT_CASH => 'Tunai',
            Order::PAYMENT_DIGITAL => 'Digital'
        ];

        $printDate = now()->format('d/m/Y H:i');
        $period = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        return view('reports-daily.print', compact('reports', 'summary', 'printDate', 'paymentMethodLabels', 'startDate', 'endDate', 'period'));
    }
}