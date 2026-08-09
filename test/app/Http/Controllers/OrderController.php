<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(\App\Models\Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function updateStatus(\Illuminate\Http\Request $request, \App\Models\Order $order)
    {
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }
