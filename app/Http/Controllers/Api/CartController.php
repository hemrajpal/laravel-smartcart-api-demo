<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\CartItem;
use App\Helpers\ApiResponse;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = CartItem::where('user_id', Auth::id())->get();
        return ApiResponse::success($cart);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $quantity = $request->quantity ?? 1;

        $cartItem = CartItem::where('user_id', $userId)
                ->where('product_id', $request->product_id)
                ->first();

        if ($cartItem) {
            // update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // create new
            $cartItem = CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $quantity
            ]);
        }

        return ApiResponse::success($cartItem, 'Product has been added to the cart');
    }

    public function updateQuantity(Request $request, $id)
    {
        $userId = Auth::id();

        $validator = \Validator::make($request->all(), [
            'quantity' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed', $validator->errors(), 422);
        }

        $cartItem = CartItem::where('user_id', $userId)
                ->where('id', $id)
                ->first();

        if(!$cartItem){
            return ApiResponse::error('Cart not found', [], 404);
        }

        if($request->quantity === 0){
            $cartItem->delete();

            return ApiResponse::success([], 'Cart has been deleted');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return ApiResponse::success($cartItem, 'Cart quantity has been updated');
    }

    public function delete(Request $request, $id)
    {
        $userId = Auth::id();

        $cartItem = CartItem::where('user_id', $userId)->where('id', $id)->first();

        if(!$cartItem){
            return ApiResponse::error('Cart not found', [], 404);
        }

        $cartItem->delete();

        return ApiResponse::success($cartItem, 'Cart has been deleted');
    }

    public function clear(Request $request)
    {
        $userId = Auth::id();

        CartItem::where('user_id', $userId)->delete();

        return ApiResponse::success([], 'Cart has been cleared');
    }
}
