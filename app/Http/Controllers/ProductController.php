<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($cat, $product_alias)
    {
        $product = Product::where('alias', $product_alias)->first();

        return view('products.show', [
            'product' => $product
        ]);
    }

    public function showCategory(Request $request, $alias)
    {
        $cat = Category::where('alias', $alias)->first();

        $pageSize = 5;
        $products = Product::where('category_id', $cat->id)->paginate($pageSize);

        if (isset($request->orderBy)) {
            if ($request->orderBy == 'price-low-high') {
                $products = Product::where('category_id', $cat->id)->orderBy('price')->paginate($pageSize);
            }
            if ($request->orderBy == 'price-high-low') {
                $products = Product::where('category_id', $cat->id)->orderBy('price', 'DESC')->paginate($pageSize);
            }
            if ($request->orderBy == 'name-a-z') {
                $products = Product::where('category_id', $cat->id)->orderBy('title')->paginate($pageSize);
            }
            if ($request->orderBy == 'name-z-a') {
                $products = Product::where('category_id', $cat->id)->orderBy('title', 'DESC')->paginate($pageSize);
            }
        }
        if ($request->ajax()) {
            return view('ajax.order-by', [
                'products' => $products
            ])->render();
        }

        return view('categories.index', compact('cat', 'products'));
    }
}
