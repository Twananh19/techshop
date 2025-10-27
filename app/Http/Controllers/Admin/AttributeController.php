<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use App\Models\Category;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = ProductAttribute::with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.attributes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'unit' => 'nullable|string|max:50',
        ]);

        ProductAttribute::create($validated);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Thuộc tính đã được tạo thành công!');
    }

    public function edit(ProductAttribute $attribute)
    {
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.attributes.edit', compact('attribute', 'categories'));
    }

    public function update(Request $request, ProductAttribute $attribute)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'unit' => 'nullable|string|max:50',
        ]);

        $attribute->update($validated);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Thuộc tính đã được cập nhật thành công!');
    }

    public function destroy(ProductAttribute $attribute)
    {
        // Check if attribute has values
        if ($attribute->attributeValues()->count() > 0) {
            return back()->with('error', 'Không thể xóa thuộc tính đang được sử dụng!');
        }

        $attribute->delete();

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Thuộc tính đã được xóa thành công!');
    }
}
