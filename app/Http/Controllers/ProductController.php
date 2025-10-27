<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with(['inventoryItem.category'])->findOrFail($id);
        $relatedProducts = Product::where('id', '!=', $id)
            ->whereHas('inventoryItem', function($query) use ($product) {
                $query->where('category_id', $product->inventoryItem->category_id);
            })
            ->where('status', 'active')
            ->limit(4)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::whereHas('inventoryItem', function($query) use ($category) {
            $query->where('category_id', $category->id);
        })
        ->where('status', 'active')
        ->paginate(12);
        
        return view('products.category', compact('category', 'products'));
    }
}
