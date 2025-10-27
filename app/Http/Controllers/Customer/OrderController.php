<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.product', 'payment'])
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng đang chờ xử lý!');
        }

        $order->status = 'cancelled';
        $order->save();

        // Hoàn lại tồn kho
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return back()->with('success', 'Đơn hàng đã được hủy thành công!');
    }
}
