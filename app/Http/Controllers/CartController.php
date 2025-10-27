<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        
        return view('cart.index', compact('cartItems', 'cart'));
    }

    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($productId);
        
        // Kiểm tra tồn kho
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Sản phẩm không đủ số lượng trong kho!');
        }

        $cart = $this->getOrCreateCart();
        
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Số lượng vượt quá tồn kho!');
            }
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($itemId);
        
        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Số lượng vượt quá tồn kho!');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);
        $cartItem->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
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

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => Session::getId()]
            );
        } else {
            $sessionId = Session::getId();
            return Cart::firstOrCreate(
                ['session_id' => $sessionId]
            );
        }
    }
}
