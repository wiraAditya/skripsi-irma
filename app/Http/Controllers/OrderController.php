<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $statusConfig = [
        Order::STATUS_WAITING_CASH => [
            'class' => 'bg-yellow-100 text-yellow-800',
            'label' => 'Menunggu Pembayaran'
        ],
        Order::STATUS_PAID => [
            'class' => 'bg-blue-100 text-blue-800',
            'label' => 'Dibayar'
        ],
        Order::STATUS_PROCESS => [
            'class' => 'bg-purple-100 text-purple-800',
            'label' => 'Diproses'
        ],
        Order::STATUS_DONE => [
            'class' => 'bg-green-100 text-green-800',
            'label' => 'Selesai'
        ],
        Order::STATUS_CANCELED => [
            'class' => 'bg-red-100 text-red-800',
            'label' => 'Dibatalkan'
        ],
    ];
    protected $paymentMethodLabels = [
        Order::PAYMENT_CASH => 'Tunai',
        Order::PAYMENT_DIGITAL => 'Digital'
    ];
    public function index(Request $request)
    {
        $kode = $request->query('kode') ?? null;
        $date = $request->query('date') ?? Carbon::now()->format('Y-m-d');
        $mejaId = $request->query('meja') ?? null;

        $orders = Order::with('meja')
            ->where('transaction_code', 'like', '%' . $request->search . '%')
            ->when($kode, function ($query) use ($kode) {
                return $query->where('transaction_code', 'like', '%' . $kode . '%');
            })
            ->when($date, function ($query) use ($date) {
                return $query->whereDate('tanggal', $date);
            })
            ->when($mejaId, function ($query) use ($mejaId) {
                return $query->where('meja_id', $mejaId);
            })
            ->latest()
            ->paginate(10);

        $todayOrders = Order::where('status', Order::STATUS_PAID)
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->get();

        $totalPendapatan = 0;
        $pendapatanCash = 0;
        $pendapatanDigital = 0;

        foreach ($todayOrders as $key => $order) {
            if ($order->payment_method == Order::PAYMENT_CASH) {
                $pendapatanCash += $order->subtotal + $order->tax;
            }
            if ($order->payment_method == Order::PAYMENT_DIGITAL) {
                $pendapatanDigital += $order->subtotal + $order->tax;
            }
            $totalPendapatan += $order->subtotal + $order->tax;
        }

        $summary = [
            'total_pendapatan' => $totalPendapatan,
            'pendapatan_cash' => $pendapatanCash,
            'pendapatan_digital' => $pendapatanDigital,
        ];

        $mejas = Meja::all();

        return view('order.index', compact('orders', 'summary', 'mejas', 'date', 'mejaId'))->with([
            'statusConfig' => $this->statusConfig,
            'paymentMethodLabels' => $this->paymentMethodLabels
        ]);
    }
    public function detail(Order $order)
    {
        // Load relationships
        $order->load([
            'meja',
            'orderDetails.menu',
            'orderDetails.menu.kategori'
        ]);


        return view('order.details', compact('order'))->with([
            'statusConfig' => $this->statusConfig,
            'paymentMethodLabels' => $this->paymentMethodLabels
        ]);
    }

    public function confirm(Order $order)
    {
        $order->update([
            'status' => Order::STATUS_PAID
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function edit(Order $order)
    {
        $order->load(['meja', 'orderDetails.menu']);

        $activeMenus = Menu::active()->with('kategori')->get()->groupBy('kategori.nama');

        return view('order.edit', compact('order', 'activeMenus'))->with([
            'statusConfig' => $this->statusConfig,
            'paymentMethodLabels' => $this->paymentMethodLabels
        ]);
    }

    public function update(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'catatan' => 'nullable|string',
                'items' => 'required|array',
                'items.*.id' => 'nullable|exists:order_details,id',
                'items.*.menu_id' => 'required|exists:menu,id',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.catatan' => 'nullable|string',
                'items.*.harga' => 'required|numeric|min:0',
            ]);

            DB::transaction(function () use ($order, $validated) {
                // Update order basic info
                $order->update([
                    'catatan' => $validated['catatan'] ?? "",
                ]);

                // Get only IDs for comparison to reduce memory usage
                $existingDetailIds = $order->orderDetails()->pluck('id')->toArray();
                $updatedDetailIds = [];

                foreach ($validated['items'] as $itemData) {
                    if (isset($itemData['id']) && in_array($itemData['id'], $existingDetailIds)) {
                        // Update existing item
                        $order->orderDetails()->where('id', $itemData['id'])->update([
                            'menu_id' => $itemData['menu_id'],
                            'qty' => $itemData['qty'],
                            'catatan' => $itemData['catatan'] ?? "",
                            'harga' => $itemData['harga'],
                        ]);
                        $updatedDetailIds[] = $itemData['id'];
                    } else {
                        // Add new item
                        $detail = $order->orderDetails()->create([
                            'menu_id' => $itemData['menu_id'],
                            'qty' => $itemData['qty'],
                            'catatan' => $itemData['catatan'] ?? "",
                            'harga' => $itemData['harga'],
                        ]);

                        if (isset($itemData['id'])) {
                            $updatedDetailIds[] = $itemData['id'];
                        }
                    }
                }

                // Remove deleted items efficiently
                if (!empty($existingDetailIds)) {
                    $itemsToDelete = array_diff($existingDetailIds, $updatedDetailIds);
                    if (!empty($itemsToDelete)) {
                        $order->orderDetails()->whereIn('id', $itemsToDelete)->delete();
                    }
                }

                // Recalculate order total
                $order->recalculateTotal();
            });

            return redirect()->back()
                ->with('success', 'Pesanan berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali data pesanan Anda.');
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi nanti. ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Order update error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pesanan. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function destroyDetail(Order $order, OrderDetails $detail)
    {
        if ($detail->order_id !== $order->id) {
            abort(403);
        }

        $detail->delete();
        $order->recalculateTotal();

        return back()->with('success', 'Item berhasil dihapus');
    }
}
