<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Services\MidtransService;

class PaymentController extends HomeBaseController
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    public function index(Request $request)
    {
        $tableName = $request->query('mejaId');
        $table = null;

        if ($tableName) {
            $table = Meja::where('unique_code', $tableName)->first();
        }
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->to('/?mejaId=' . $tableName)->with('error', 'Your cart is empty. Please add items before proceeding to payment.');
        }
        $subTotal = 0;
        if (count($cartItems) > 0) {
            foreach ($cartItems as $item) {
                $subTotal += $item['price'] * $item['quantity'];
            }
        }

        return view('home.payment', compact('cartItems', 'subTotal', 'table'));
    }

    public function paid(Request $request)
    {
        $method = in_array($request->input('method'), ["method_cash", "method_digital"]) ? $request->input('method') : "method_cash";

        $cartItems = session()->get('cart', []);
        $mejaId = $request->input('mejaId');
        $meja = Meja::where('unique_code', $mejaId)->firstOrFail();

        if (empty($cartItems)) {
            return redirect()->to('/?mejaId=' . $tableName)->with('error', 'Your cart is empty. Please add items before proceeding to payment.');
        }

        $subTotal = 0;
        foreach ($cartItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }
        $tax = $subTotal * 0.10;

        $status = ($method === 'method_cash') ? 'status_waiting_cash' : 'status_paid';

        $transactionCode = 'TRX-' . time() . '-' . rand(1000, 9999);

        $order = Order::create([
            'meja_id' => $meja->id,
            'tanggal' => now(),
            'subtotal' => $subTotal,
            'tax' => $tax,
            'payment_method' => $method,
            'transaction_code' => $transactionCode,
            'catatan' => $request->catatan ?? '',
            'nama' => $request->nama ?? '',
            'status' => $status,
        ]);

        foreach ($cartItems as $item) {
            OrderDetails::create([
                'order_id' => $order->id,
                'menu_id' => $item['itemId'],
                'qty' => $item['quantity'],
                'harga' => $item['price'],
                'catatan' => $item['notes'] ?? '',
            ]);
        }

        session()->forget('cart');

        return view('payment-success', [
            'transactionCode' => $transactionCode,
            'mejaId' => $meja->unique_code,
            'method' => $method
        ]);
    }

    public function print($transactionCode)
    {
        $transaction = Order::with(['meja', 'orderDetails.menu'])
            ->where('transaction_code', $transactionCode)
            ->firstOrFail();
        return view('receipt.print', compact('transaction'));
    }

    public function token()
    {
        $cartItems = session()->get('cart', []);

        $subTotal = 0;
        foreach ($cartItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }
        $transactionCode = 'TRX-' . time() . '-' . rand(1000, 9999);

        $tax = $subTotal * (10 / 100);
        $gt = $subTotal + $tax;
        $params = [
            'transaction_details' => [
                'order_id' => $transactionCode,
                'gross_amount' => $gt,
            ],
        ];

        try {
            // Create Snap transaction
            $snapToken = $this->midtransService->createTransaction($params);

            // Return view with Snap token
            return response()->json($snapToken);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
}
