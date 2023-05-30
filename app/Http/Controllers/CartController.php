<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart()
    {
        $cart = session()->get('cart');
        $cartAmount = 0;
        foreach ($cart as $cartItem) {
            $cartAmount = $cartAmount + (($cartItem['quantity'] / $cartItem['volume']) * $cartItem['price']);
        }
        return response()->json(
            [
                'cart' => $cart,
                'count' => count($cart),
                'cartAmount' => $cartAmount,
            ]
        );
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $productName = $request->input('product_name');
        $productPrice = floatval($request->input('product_price'));
        $quantity = intval($request->input('quantity'));
        $volume = intval($request->input('volume'));

        $stock = Stock::find($productId);
        $remainedQuantity = intval($stock->quantity);
        $unit = $stock->unit;
        $cart = session()->get('cart', []);

        // Check if the cart quantity exceeds the remained quantity in stock
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($newQuantity > $remainedQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The remained quantity in stock is ' . $remainedQuantity,
                ]);
            }
            $cart[$productId]['quantity'] += $quantity;
            session()->put('cart', $cart);
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => $quantity,
                'volume' => $volume,
                'unit' => $unit,
                'remainedQuantity' => $remainedQuantity
            ];
            session()->put('cart', $cart);
        }

        $cartAmount = 0;
        foreach ($cart as $cartItem) {
            $cartAmount += ($cartItem['quantity'] / $cartItem['volume']) * $cartItem['price'];
        }

        return response()->json([
            'success' => true,
            'totalQuantity' => count($cart),
            'totalPrice' => $cartAmount,
            'cart' => $cart
        ]);
    }


    public function updateCart(Request $request)
    {
        $productId = intval($request->input('product_id'));
        $quantity = floatval($request->input('quantity'));

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);

            $cartAmount = 0;
            foreach ($cart as $cartItem) {
                $cartAmount = $cartAmount + (($cartItem['quantity'] / $cartItem['volume']) * $cartItem['price']);
            }

            return response()->json([
                'success' => true,
                'totalQuantity' => count($cart),
                'totalPrice' => $cartAmount,
                'cart' => $cart
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ]);
        }
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        $cartAmount = 0;
        foreach ($cart as $cartItem) {
            $cartAmount = $cartAmount + (($cartItem['quantity'] / $cartItem['volume']) * $cartItem['price']);
        }
        return response()->json([
            'success' => true,
            'totalQuantity' => count($cart),
            'totalPrice' => $cartAmount,
            'cart' => $cart
        ]);
    }

    public function emptyCart()
    {
        session()->forget('cart');
        $cart = session()->get('cart', []);
        session()->put('cart', $cart);
        return response()->json([
            'success' => true,
            'totalQuantity' => 0,
            'totalPrice' => 0,
            'cart' => $cart
        ]);
    }
    public function cartCheckout()
    {
        session()->forget('cart');
        $cart = session()->get('cart', []);
        session()->put('cart', $cart);

        return view('checkout', compact($cart));
        // return response()->json([
        //     'success' => true,
        //     'totalQuantity' => 0,
        //     'totalPrice' => 0,
        //     'cart' => $cart
        // ]);
    }
}
