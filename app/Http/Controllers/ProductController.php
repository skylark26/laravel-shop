<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($cat, $product_id)
    {
        $product = Product::where('id', $product_id)->first();

        return view('product.show', [
            'product' => $product
        ]);
    }
}
