<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Product::where('stock', '>', 0);
        
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();
        return view('store.index', compact('products'));
    }

    public function show(\App\Models\Product $product)
    {
        return view('store.show', compact('product'));
    }
}
