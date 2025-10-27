<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'payment']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order ID or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.inventoryItem', 'items.inventoryItem', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $products = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->with('inventoryItem')
            ->get();

        return view('admin.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'shipping_name' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cod,credit_card,paypal,bank_transfer',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total
            $total = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::with('inventoryItem')->findOrFail($item['product_id']);
                
                // Check stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ hàng!");
                }

                $price = $product->discount_price ?? $product->price;
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'inventory_item_id' => $product->inventory_item_id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            // Create order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);

                // Update inventory stock
                $product->inventoryItem->decrement('stock_quantity', $item['quantity']);

                // Create inventory transaction
                InventoryTransaction::create([
                    'inventory_item_id' => $item['inventory_item_id'],
                    'type' => 'export',
                    'quantity' => -$item['quantity'],
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'note' => "Xuất hàng cho đơn #{$order->id}",
                    'created_by' => auth()->id(),
                ]);
            }

            // Create payment record
            $order->payment()->create([
                'method' => $validated['payment_method'],
                'amount' => $total,
                'status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
            ]);

            DB::commit();

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Đơn hàng đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // If order is cancelled, restore stock
        if ($validated['status'] === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->inventoryItem->increment('stock_quantity', $item->quantity);

                // Create inventory transaction
                InventoryTransaction::create([
                    'inventory_item_id' => $item->inventory_item_id,
                    'type' => 'return',
                    'quantity' => $item->quantity,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'note' => "Hoàn hàng từ đơn #{$order->id} (đã hủy)",
                    'created_by' => auth()->id(),
                ]);
            }
        }

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }

    public function destroy(Order $order)
    {
        // Only allow deleting cancelled orders
        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đã hủy!');
        }

        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được xóa thành công!');
    }
}
