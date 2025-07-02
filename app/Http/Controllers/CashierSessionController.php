<?php

namespace App\Http\Controllers;

use App\Models\CashierSession;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierSessionController extends Controller
{
    public function open()
    {
        return view('sessions.open', [
            'activeRoute' => 'order',
        ]);
    }

    public function storeOpen(Request $request)
    {
        $validated = $request->validate([
            'starting_cash' => 'required|numeric'
        ]);

        $userId = Auth::user()->id;

        // Check for existing open session
        if (CashierSession::where('user_id', $userId)
            ->where('status', 'open')
            ->exists()) {
            return redirect()->route('order.index')
                ->with('error', 'You already have an open session');
        }

        CashierSession::create([
            'user_id' => $userId,
            'start_time' => now(),
            'starting_cash' => $validated['starting_cash'],
            'status' => 'open'
        ]);

        return redirect()->route('order.index')
            ->with('success', 'Cashier session opened successfully');
    }

    public function close($sessionId)
    {
        $session = CashierSession::findOrFail($sessionId);
        
        if ($session->status === 'closed') {
            return redirect()->route('order.index')
                ->with('error', 'Invalid session');
        }

        return view('sessions.close', [
            'session' => $session,
            'activeRoute' => 'order',
        ]);
    }

    public function storeClose(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'ending_cash' => 'required|numeric'
        ]);

        $session = CashierSession::findOrFail($sessionId);
        
        if ($session->status === 'closed') {
            return redirect()->route('order.index')
                ->with('error', 'Invalid session');
        }

        // Calculate expected cash - only count PAID, PROCESS, DONE statuses
        $cashOrders = Order::where('user_id', $sessionId)
            ->where('payment_method', Order::PAYMENT_CASH)
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESS,
                Order::STATUS_DONE
            ])
            ->selectRaw('SUM(subtotal + tax) as total')
            ->value('total');

        $expectedCash = $session->starting_cash + $cashOrders;
        $endingCash = $validated['ending_cash'];
        $discrepancy = $endingCash - $expectedCash;

        $session->update([
            'end_time' => now(),
            'ending_cash' => $endingCash,
            'expected_cash' => $expectedCash,
            'status' => 'closed',
            'discrepancy' => $discrepancy
        ]);

        // Prepare success message with financial summary
        $discrepancyFormatted = number_format(abs($discrepancy));
        $message = sprintf(
            "Sesi kasir ditutup. Saldo akhir: Rp %s | Selisih: Rp %s (%s)",
            number_format($endingCash),
            $discrepancyFormatted,
            ($discrepancy >= 0) ? 'lebih' : 'kurang'
        );

        return redirect()->route('order.index')
            ->with('success', $message);
    }
}