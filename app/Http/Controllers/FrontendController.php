<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $table = null;
        if (isset($request->table)) {
            $table = Table::where('name', $request->table)->first();
        }

        $menuCategories = MenuCategory::all();

        $menus = Menu::query()
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('menu_category_id', $request->category_id);
            })
            ->active()
            ->orderBy('menu_category_id')
            ->get();

        return view('order', compact('menus', 'menuCategories', 'table'));
    }

    public function addToCart(Request $request)
    {
        DB::beginTransaction();
        try {
            $table = Table::where('name', $request->table)->first();
            if (!$table) {
                throw new Exception('Table not found.');
            }

            $menu = Menu::find($request->menu_id);
            if (!$menu) {
                throw new Exception('Menu not found.');
            }

            $order = Order::query()
                ->where('table_id', $table->id)
                ->whereDate('date', date('Y-m-d'))
                ->where('payment_status', 1)
                ->first();

            if (!$order) {
                $order = new Order();
                $order->code = $this->generateCode();
                $order->table_id = $table->id;
                $order->date = date('Y-m-d');
                $order->save();
            }

            $orderDetail = OrderDetail::query()
                ->where('order_id', $order->id)
                ->where('menu_id', $menu->id)
                ->first();

            if ($orderDetail) {
                $orderDetail->quantity += $request->quantity;
                $orderDetail->total_price = $menu->price * $orderDetail->quantity;
                $orderDetail->save();
                $message = 'Quantity updated.';
            } else {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->menu_id = $menu->id;
                $orderDetail->quantity = $request->quantity;
                $orderDetail->price = $menu->price;
                $orderDetail->total_price = $menu->price * $request->quantity;
                $orderDetail->save();
                $message = 'Menu added to cart successfully.';
            }

            $order->total_price = $order->details()->sum('total_price');
            $order->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 400);
        }
        return response()->json(['message' => $message]);
    }

    public function generateCode()
    {
        $prefix = 'ADR';
        $today = Carbon::today()->format('Ymd');

        $countToday = DB::table('orders') // Ganti your_table_name
            ->whereDate('created_at', Carbon::today())
            ->count();

        $nextNumber = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);

        $code = "{$prefix}{$today}{$nextNumber}";

        return $code;
    }

    public function checkout(Request $request)
    {
        $table = Table::where('name', $request->table)->first();
        $order = Order::query()
            ->with('details.menu')
            ->where('table_id', $table->id)
            ->whereDate('date', date('Y-m-d'))
            ->where('payment_status', 1)
            ->first();

        if (!$order || $order->details == null) {
            $notification = array(
                'message'    => 'Please select one menu first.',
                'alert-type' => 'error'
            );

            return redirect()->route('index', ['table' => $table->name])->with($notification);
        }

        return view('checkout', compact(['order', 'table']));
    }

    public function checkoutStore(Request $request)
    {
        $table = Table::find($request->table_id);
        DB::beginTransaction();
        try {
            $order = Order::query()
                ->where('table_id', $table->id)
                ->whereDate('date', date('Y-m-d'))
                ->where('payment_status', 1)
                ->first();

            $paymentMethod = $request->payment_method;

            if ($paymentMethod == 'midtrans') {
                return response()->json([
                    'redirect_url' => 'asd'
                ]);
            }

            $order->payment_status = 2;
            $order->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Payment Success! Please pay at the cashier.'
        ]);
    }
}
