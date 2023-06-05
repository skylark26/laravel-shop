<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($cat, $product_id)
    {
        $product = Product::where('id', $product_id)->first();

        return view('products.show', [
            'product' => $product
        ]);
    }

    public function showCategory($alias)
    {
        $cat = Category::where('alias', $alias)->first();
        return view('categories.index', [
            'cat' => $cat
        ]);
    }
}
