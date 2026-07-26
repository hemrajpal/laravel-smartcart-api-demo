<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ApiResponse;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $userId = Auth::id();

        $cartItems = \App\Models\CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        if($cartItems->isEmpty()) {
            return ApiResponse::error('Cart is empty', [], 400);
        }

        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->product->price * $item->quantity;
            }

            // Create order
            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);
            }

            // Clear cart
            \App\Models\CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return ApiResponse::success($order, 'Order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::error('Something went wrong', $e->getMessage(), 500);
        }
    }

    public function list(Request $request)
    {
        $orders = \App\Models\Order::get();

        return ApiResponse::success($orders);
    }

    public function show(Request $request, $id)
    {
        $order = \App\Models\Order::with('orderItems.product', 'address', 'payment')->where('user_id', Auth::id())->where('id', $id)->first();

        if (!$order) {
            return ApiResponse::error('Order not found', [], 404);
        }

        return ApiResponse::success($order);
    }

    public function updateAdress($id, $address_id)
    {
        $order = \App\Models\Order::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$order) {
            return ApiResponse::error('Order not found', [], 404);
        }

        $order->address_id = $address_id;
        $order->save();

        return ApiResponse::success($order, 'Address added');
    }
}
