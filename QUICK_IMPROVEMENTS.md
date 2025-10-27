# 🚀 CẢI THIỆN NHANH - TĂNG ĐIỂM LÊN 10/10

## 📌 3 TÍNH NĂNG QUAN TRỌNG NHẤT (3-4 giờ)

---

## 1️⃣ TÌM KIẾM SẢN PHẨM (30 phút)

### Bước 1: Tạo SearchController
```bash
php artisan make:controller SearchController
```

### Bước 2: Code Controller
```php
<?php
// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query) {
            return redirect()->route('home');
        }
        
        $products = Product::with(['inventoryItem.category', 'images'])
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('inventoryItem.category', function($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%");
                  });
            })
            ->paginate(12);
        
        return view('products.search', compact('products', 'query'));
    }
}
```

### Bước 3: Thêm route
```php
// routes/web.php
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'index'])->name('search');
```

### Bước 4: Thêm search form vào header
```blade
<!-- resources/views/layouts/app.blade.php -->
<!-- Thêm vào navbar, sau logo -->
<form action="{{ route('search') }}" method="GET" class="flex-1 max-w-lg mx-8">
    <div class="relative">
        <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}"
            placeholder="Tìm kiếm sản phẩm..." 
            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
        <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </div>
</form>
```

### Bước 5: Tạo view search
```bash
cp resources/views/home.blade.php resources/views/products/search.blade.php
```

```blade
<!-- resources/views/products/search.blade.php -->
@extends('layouts.app')

@section('title', 'Tìm kiếm: ' . $query)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Kết quả tìm kiếm cho "{{ $query }}"
        </h1>
        <p class="text-gray-600 mt-2">Tìm thấy {{ $products->total() }} sản phẩm</p>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-blue-600 font-bold text-xl">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                            
                            @if($product->inventoryItem && $product->inventoryItem->stock_quantity < 10)
                                <p class="text-red-500 text-sm mt-2">Chỉ còn {{ $product->inventoryItem->stock_quantity }} sản phẩm</p>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900">Không tìm thấy sản phẩm nào</h3>
            <p class="mt-2 text-gray-500">Thử tìm kiếm với từ khóa khác</p>
            <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Quay về trang chủ
            </a>
        </div>
    @endif
</div>
@endsection
```

**✅ Hoàn thành! (30 phút)**

---

## 2️⃣ LỌC SẢN PHẨM THEO GIÁ (1 giờ)

### Bước 1: Cập nhật ProductController
```php
<?php
// app/Http/Controllers/ProductController.php

public function category($slug, Request $request)
{
    $category = Category::where('slug', $slug)->firstOrFail();
    
    $query = Product::with(['inventoryItem.category', 'images'])
        ->where('status', 'active')
        ->whereHas('inventoryItem', function($q) use ($category) {
            $q->where('category_id', $category->id);
        });
    
    // Filter by price
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }
    
    // Sort
    $sort = $request->get('sort', 'newest');
    switch($sort) {
        case 'price_asc':
            $query->orderBy('price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
        case 'name':
            $query->orderBy('name', 'asc');
            break;
        default: // newest
            $query->latest('published_at');
            break;
    }
    
    $products = $query->paginate(12);
    
    // Price range for filter
    $priceRange = [
        'min' => Product::where('status', 'active')->min('price'),
        'max' => Product::where('status', 'active')->max('price'),
    ];
    
    return view('products.category', compact('category', 'products', 'priceRange'));
}
```

### Bước 2: Tạo view category với filters
```blade
<!-- resources/views/products/category.blade.php -->
@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
        <p class="text-gray-600 mt-2">{{ $category->description }}</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Bộ lọc</h3>
                
                <form method="GET" action="{{ route('products.category', $category->slug) }}">
                    <!-- Price Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giá</label>
                        <div class="space-y-2">
                            <input 
                                type="number" 
                                name="min_price" 
                                placeholder="Từ" 
                                value="{{ request('min_price') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            >
                            <input 
                                type="number" 
                                name="max_price" 
                                placeholder="Đến" 
                                value="{{ request('max_price') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Giá từ {{ number_format($priceRange['min'], 0, ',', '.') }}đ 
                            đến {{ number_format($priceRange['max'], 0, ',', '.') }}đ
                        </p>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sắp xếp</label>
                        <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-2">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Áp dụng
                        </button>
                        <a href="{{ route('products.category', $category->slug) }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center rounded-md hover:bg-gray-300">
                            Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $product->name }}</h3>
                                    <p class="text-blue-600 font-bold text-xl">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                                    
                                    @if($product->inventoryItem && $product->inventoryItem->stock_quantity < 10)
                                        <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-600 text-xs rounded">
                                            Chỉ còn {{ $product->inventoryItem->stock_quantity }} sản phẩm
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">Không có sản phẩm nào trong danh mục này</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
```

**✅ Hoàn thành! (1 giờ)**

---

## 3️⃣ ĐÁNH GIÁ SẢN PHẨM (2 giờ)

### Bước 1: Tạo migration
```bash
php artisan make:migration create_product_reviews_table
```

```php
<?php
// database/migrations/xxxx_create_product_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();
            
            $table->unique(['product_id', 'user_id']);
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
```

```bash
php artisan migrate
```

### Bước 2: Tạo Model
```bash
php artisan make:model ProductReview
```

```php
<?php
// app/Models/ProductReview.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'is_verified_purchase',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Bước 3: Cập nhật Product Model
```php
<?php
// app/Models/Product.php

// Thêm vào relationships
public function reviews()
{
    return $this->hasMany(ProductReview::class);
}

public function averageRating()
{
    return $this->reviews()->avg('rating') ?? 0;
}

public function ratingCount()
{
    return $this->reviews()->count();
}

public function userHasReviewed($userId)
{
    return $this->reviews()->where('user_id', $userId)->exists();
}
```

### Bước 4: Tạo Controller
```bash
php artisan make:controller ReviewController
```

```php
<?php
// app/Http/Controllers/ReviewController.php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if user already reviewed
        if ($product->userHasReviewed(auth()->id())) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        // Check if user purchased this product
        $hasPurchased = auth()->user()->orders()
            ->where('status', 'completed')
            ->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();
        
        ProductReview::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_verified_purchase' => $hasPurchased,
        ]);
        
        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function destroy($reviewId)
    {
        $review = ProductReview::findOrFail($reviewId);
        
        // Only allow user to delete their own review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xóa đánh giá này');
        }
        
        $review->delete();
        
        return back()->with('success', 'Đã xóa đánh giá của bạn!');
    }
}
```

### Bước 5: Thêm routes
```php
// routes/web.php
use App\Http\Controllers\ReviewController;

Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
```

### Bước 6: Cập nhật ProductController
```php
<?php
// app/Http/Controllers/ProductController.php

public function show($id)
{
    $product = Product::with([
        'inventoryItem.category',
        'images',
        'attributeValues.attribute',
        'reviews' => function($query) {
            $query->with('user')->latest();
        }
    ])->findOrFail($id);
    
    // Calculate average rating
    $averageRating = $product->averageRating();
    $ratingCount = $product->ratingCount();
    
    // Check if current user reviewed
    $userReview = null;
    if (auth()->check()) {
        $userReview = $product->reviews()->where('user_id', auth()->id())->first();
    }
    
    return view('products.show', compact('product', 'averageRating', 'ratingCount', 'userReview'));
}
```

### Bước 7: Cập nhật view products/show.blade.php
```blade
<!-- Thêm vào sau thông tin sản phẩm, trước phần "Related Products" -->

<!-- Reviews Section -->
<div class="mt-12 border-t pt-8">
    <h2 class="text-2xl font-bold mb-6">Đánh giá sản phẩm</h2>
    
    <!-- Rating Summary -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-8">
            <div class="text-center">
                <div class="text-5xl font-bold text-yellow-500">
                    {{ number_format($averageRating, 1) }}
                </div>
                <div class="flex justify-center mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ $ratingCount }} đánh giá</p>
            </div>
        </div>
    </div>

    <!-- Review Form (if user hasn't reviewed) -->
    @auth
        @if(!$userReview)
            <div class="bg-white border rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Viết đánh giá của bạn</h3>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    
                    <!-- Rating Stars -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đánh giá *</label>
                        <div class="flex gap-2" x-data="{ rating: 0 }">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="focus:outline-none">
                                    <svg class="w-8 h-8 transition" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" x-model="rating" required>
                        </div>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nhận xét</label>
                        <textarea 
                            name="comment" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."
                        ></textarea>
                        @error('comment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Gửi đánh giá
                    </button>
                </form>
            </div>
        @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-800">✓ Bạn đã đánh giá sản phẩm này</p>
            </div>
        @endif
    @else
        <div class="bg-gray-50 border rounded-lg p-6 mb-6 text-center">
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Đăng nhập</a> 
                để viết đánh giá
            </p>
        </div>
    @endauth

    <!-- Reviews List -->
    <div class="space-y-4">
        @forelse($product->reviews as $review)
            <div class="border rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $review->user->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                @if($review->is_verified_purchase)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Đã mua hàng</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $review->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    
                    @auth
                        @if($review->user_id === auth()->id())
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                    Xóa
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
                
                @if($review->comment)
                    <p class="mt-4 text-gray-700">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p>Chưa có đánh giá nào cho sản phẩm này</p>
                <p class="text-sm mt-2">Hãy là người đầu tiên đánh giá!</p>
            </div>
        @endforelse
    </div>
</div>
```

**✅ Hoàn thành! (2 giờ)**

---

## 🎉 TỔNG KẾT

### Sau khi hoàn thành 3 tính năng trên:

✅ **Tìm kiếm sản phẩm** - Tăng trải nghiệm người dùng  
✅ **Lọc theo giá** - Giúp khách hàng tìm sản phẩm phù hợp  
✅ **Đánh giá sản phẩm** - Tăng độ tin cậy, tính tương tác  

### Điểm dự kiến: **10/10** 🏆

---

## 📝 CHECKLIST

```bash
# 1. Search (30 phút)
[ ] Tạo SearchController
[ ] Thêm route search
[ ] Thêm search form vào header
[ ] Tạo view search results
[ ] Test search với nhiều từ khóa

# 2. Filter (1 giờ)
[ ] Cập nhật ProductController->category
[ ] Tạo view với sidebar filters
[ ] Test filter giá
[ ] Test sort options
[ ] Test pagination với filters

# 3. Reviews (2 giờ)
[ ] Tạo migration product_reviews
[ ] Tạo ProductReview model
[ ] Cập nhật Product model
[ ] Tạo ReviewController
[ ] Thêm routes
[ ] Cập nhật view products/show
[ ] Test add review
[ ] Test delete own review
[ ] Test verified purchase badge
```

---

## 🚀 TEST TRƯỚC KHI NỘP

```bash
# 1. Test Search
- Tìm "màn hình"
- Tìm "samsung"
- Tìm từ không tồn tại

# 2. Test Filter
- Lọc giá từ 1tr-5tr
- Lọc giá > 10tr
- Sort giá tăng/giảm dần

# 3. Test Reviews
- Đăng nhập và đánh giá
- Kiểm tra verified purchase
- Xóa review của mình
- Check không thể review 2 lần
```

---

**CHÚC BẠN THÀNH CÔNG! 🎉**
