<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meja;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $itemId = $request->itemId;
        $name = $request->name;
        $price = $request->price;
        $quantity = $request->quantity;
        $image = $request->image;
        $notes = $request->notes;

        // Get existing cart or create empty array
        $cart = session()->get('cart', []);

        // Create a unique key for the item in cart
        $cartKey = $itemId;

        // Add item to cart
        $cart[$cartKey] = [
            'itemId' => $itemId,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image,
            'notes' => $notes
        ];

        // Save cart to session
        session()->put('cart', $cart);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Item added to cart!');
    }

    /**
     * Display the cart page
     */
    public function index(Request $request)
    {
        $tableName = $request->query('mejaId');
        $table = null;

        if ($tableName) {
            $table = Meja::where('unique_code', $tableName)->first();
        }
        $cartItems = session()->get('cart', []);

        $subTotal = 0;
        if (count($cartItems) > 0) {
            foreach ($cartItems as $item) {
                $subTotal += $item['price'] * $item['quantity'];
            }
        }

        return view('home.cart', compact('cartItems', 'subTotal', 'table'));
    }

    /**
     * Update item in cart
     */
    public function update(Request $request)
    {
        $index = $request->itemIndex;
        $itemId = $request->itemId;
        $name = $request->name;
        $price = $request->price;
        $quantity = $request->quantity;
        $image = $request->image;
        $notes = $request->notes;

        // Get current cart
        $cart = session()->get('cart', []);

        // Update the item
        if (isset($cart[$index])) {
            $cart[$index] = [
                'itemId' => $itemId,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image,
                'notes' => $notes
            ];

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function remove($index)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index', request()->query());
    }

    /**
     * Clear the entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('home', request()->query());
    }
}
