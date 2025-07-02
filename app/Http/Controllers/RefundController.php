<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Refund;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with(['order', 'processedBy', 'items.orderDetail.menu'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('refunds.index', compact('refunds'));
    }

    public function create(Request $request)
    {
        $order = null;
        $step = 1;
        
        if ($request->has('order_id')) {
            $request->validate([
                'order_id' => 'required|string'
            ]);
            
            $searchTerm = $request->order_id;
            
            // Search by ID or transaction code
            $order = Order::with(['orderDetails.menu', 'orderDetails.refundItems'])
                ->where('id', $searchTerm)
                ->orWhere('transaction_code', $searchTerm)
                ->first();
                
            if ($order) {
                $step = 2;
            } else {
                return back()->withErrors([
                    'order_id' => 'Order not found with ID or transaction code: ' . $searchTerm
                ])->withInput();
            }
        }
        
        return view('refunds.create', compact('order', 'step'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array',
            'method' => 'required|in:cash,transfer',
        ], [
            'items.required' => 'Please select at least one item to refund.',
            'method.required' => 'Please select a refund method.',
            'method.in' => 'Please select a valid refund method.',
        ]);

        $order = Order::with('orderDetails.refundItems')->findOrFail($request->order_id);
        $validationErrors = [];
        $validItems = [];
        
        // Filter items that have 'id' (checked items)
        foreach ($request->items as $itemIndex => $item) {
            if (isset($item['id'])) {
                $validItems[] = $item;
                
                // Validate quantity for checked items
                if (!isset($item['qty']) || !is_numeric($item['qty']) || $item['qty'] < 1) {
                    $validationErrors["items.{$itemIndex}.qty"] = "Quantity must be at least 1.";
                }
                
                // Validate reason for checked items
                if (!isset($item['reason']) || empty(trim($item['reason']))) {
                    $validationErrors["items.{$itemIndex}.reason"] = "Please provide a reason for refunding this item.";
                } elseif (strlen($item['reason']) > 500) {
                    $validationErrors["items.{$itemIndex}.reason"] = "Reason cannot exceed 500 characters.";
                }
                
                // Validate stock availability using new method
                if (isset($item['qty']) && is_numeric($item['qty'])) {
                    $orderDetail = OrderDetails::find($item['id']);
                    if ($orderDetail) {
                        // Check against net quantity (original - already refunded)
                        $availableQty = $orderDetail->getAvailableRefundQuantity();
                        if ($item['qty'] > $availableQty) {
                            $validationErrors["items.{$itemIndex}.qty"] = "Cannot refund more than {$availableQty} items. (Original: {$orderDetail->qty}, Already refunded: {$orderDetail->getTotalRefundedQuantity()})";
                        }
                    }
                }
            }
        }
        
        // Check if at least one item is selected
        if (empty($validItems)) {
            $validationErrors['items'] = 'Please select at least one item to refund.';
        }
        
        if (!empty($validationErrors)) {
            return back()->withErrors($validationErrors)->withInput();
        }

        DB::beginTransaction();
        try {
            $totalRefund = 0;
            $refundItems = [];

            foreach ($validItems as $item) {
                $orderDetail = OrderDetails::findOrFail($item['id']);
                $qty = $item['qty'];
                $amount = $qty * $orderDetail->harga;
                
                // Double-check availability within transaction
                if (!$orderDetail->canBeRefunded($qty)) {
                    throw new \Exception("Insufficient quantity available for refund on item {$orderDetail->id}");
                }
                
                $totalRefund += $amount;
                
                $refundItems[] = [
                    'order_detail_id' => $orderDetail->id,
                    'quantity' => $qty,
                    'refund_amount' => $amount,
                    'reason' => $item['reason'],
                ];

                // DO NOT modify the original order detail quantity
                // The original data stays intact for reporting purposes
            }

            $refund = Refund::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'refund_amount' => $totalRefund,
                'reason' => 'Multiple items refunded',
                'status' => Refund::STATUS_APPROVED,
                'refund_method' => $request->method,
            ]);

            foreach ($refundItems as $item) {
                $refund->items()->create($item);
            }

            DB::commit();

            return redirect()->route('refunds.index')
                ->with('success', "Refund processed successfully. Total refund amount: " . number_format($totalRefund));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to process refund: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Refund $refund)
    {
        $refund->load(['order', 'processedBy', 'items.orderDetail.menu']);
        return view('refunds.show', compact('refund'));
    }

    // New method to get order summary with refund information
    public function getOrderSummary($orderId)
    {
        $order = Order::with(['orderDetails.menu', 'orderDetails.refundItems.refund'])
            ->findOrFail($orderId);

        return [
            'order' => $order,
            'original_total' => $order->total_amount,
            'total_refunded' => $order->getTotalRefundedAmount(),
            'net_total' => $order->getNetAmount(),
            'details' => $order->getOrderDetailsWithNetQuantities(),
        ];
    }
}