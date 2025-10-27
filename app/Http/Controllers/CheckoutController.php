<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // Lấy giỏ hàng
        $cart = $this->getCart();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $cartItems = $cart->items()->with('product')->get();
        
        // Kiểm tra tồn kho
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Sản phẩm {$item->product->name} không đủ số lượng trong kho!");
            }
        }

        // Lấy địa chỉ đã lưu (nếu user đã đăng nhập)
        $savedAddresses = Auth::check() ? UserAddress::where('user_id', Auth::id())->get() : collect();

        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $shippingFee = 0; // Miễn phí ship
        $total = $subtotal + $shippingFee;

        return view('checkout.index', compact('cartItems', 'savedAddresses', 'subtotal', 'shippingFee', 'total'));
    }

    public function process(Request $request)
    {
        // Validate thông tin
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_district' => 'nullable|string|max:100',
            'payment_method' => 'required|in:cod,bank_transfer,momo,zalopay',
            'notes' => 'nullable|string|max:1000',
            'save_address' => 'nullable|boolean',
        ]);

        $cart = $this->getCart();
        
        if (!$cart || $cart->items()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        try {
            $cartItems = $cart->items()->with('product')->get();
            
            // Tính tổng tiền
            $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
            $shippingFee = 0;
            $total = $subtotal + $shippingFee;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_district' => $validated['shipping_district'] ?? null,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $total,
                'status' => 'pending', // pending, confirmed, shipped, completed, cancelled
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Tạo order items và trừ tồn kho
            foreach ($cartItems as $item) {
                // Kiểm tra lại tồn kho
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm {$item->product->name} không đủ số lượng!");
                }

                // Tạo order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'inventory_item_id' => $item->product->inventory_item_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                // Trừ tồn kho
                $item->product->decrement('stock', $item->quantity);
            }

            // Tạo payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'status' => $validated['payment_method'] == 'cod' ? 'pending' : 'pending',
                'transaction_id' => null,
            ]);

            // Lưu địa chỉ nếu user chọn
            if (Auth::check() && $request->has('save_address') && $request->save_address) {
                UserAddress::create([
                    'user_id' => Auth::id(),
                    'full_name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'address' => $validated['shipping_address'],
                    'city' => $validated['shipping_city'],
                    'district' => $validated['shipping_district'] ?? null,
                    'is_default' => UserAddress::where('user_id', Auth::id())->count() == 0,
                ]);
            }

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['items.product', 'payment'])->findOrFail($orderId);
        
        // Kiểm tra quyền xem đơn hàng
        if (Auth::check() && $order->user_id != Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này!');
        }

        return view('checkout.success', compact('order'));
    }

    private function getCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->first();
        } else {
            $sessionId = Session::getId();
            return Cart::where('session_id', $sessionId)->first();
        }
    }
}
