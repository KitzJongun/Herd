<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('store.cart', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->stock < 1) {
            return back()->with('error', 'Product is out of stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] < $product->stock) {
                $cart[$product->id]['quantity']++;
            } else {
                return back()->with('error', 'Cannot add more than available stock.');
            }
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return back()->with('success', 'Product removed successfully');
        }
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('store.index')->with('error', 'Your cart is empty.');
        }
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('store.checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect()->route('store.index')->with('error', 'Cart is empty');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
            'status' => 'completed',
        ]);

        foreach ($cart as $id => $details) {
            $order->items()->create([
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
            
            // Decrease stock
            $product = Product::find($id);
            if ($product) {
                $product->decrement('stock', $details['quantity']);
            }
        }

        session()->forget('cart');

        return redirect()->route('cart.receipt', $order->id)->with('success', 'Order placed successfully!');
    }

    public function receipt(Order $order)
    {
        return view('store.receipt', compact('order'));
    }
}
