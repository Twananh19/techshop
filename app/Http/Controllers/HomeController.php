<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get active sliders
        $sliders = Slider::active()
            ->orderBy('order')
            ->get();

        // Get categories with product count
        $categories = Category::withCount(['inventoryItems'])
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();

        // Filter products by category if selected
        $selectedCategory = $request->get('category');
        
        $productsQuery = Product::with(['inventoryItem.category', 'images'])
            ->where('status', 'active');

        if ($selectedCategory && $selectedCategory !== 'all') {
            $productsQuery->whereHas('inventoryItem', function($q) use ($selectedCategory) {
                $q->where('category_id', $selectedCategory);
            });
        }

        // Get featured products
        $featuredProducts = (clone $productsQuery)
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(8)
            ->get();

        // Get latest products (or all products if category filter is applied)
        $latestProducts = (clone $productsQuery)
            ->latest('published_at')
            ->take(12)
            ->get();

        // Get all products for category filter
        $allProducts = $productsQuery->latest('published_at')->get();

        return view('home', compact('sliders', 'featuredProducts', 'latestProducts', 'categories', 'selectedCategory', 'allProducts'));
    }
}
