<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string|max:500',
            'link' => 'nullable|string|max:500',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider đã được tạo thành công!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|string|max:500',
            'link' => 'nullable|string|max:500',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider đã được cập nhật thành công!');
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider đã được xóa thành công!');
    }
}
