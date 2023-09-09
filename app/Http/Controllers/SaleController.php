<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Customer;
use App\Models\Good;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Services\MessagingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as REQ;

class SaleController extends Controller
{

    public function getAllGoods(Request $request)
    {
        if (REQ::is('api/*')) {
            $goods=Good::all();
            return response()->json([
                'goods'=>$goods,
                'status'=>true,
            ]);
        }
    }

    // SELL PRODUCT
    public function sellProduct(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            if (!$order) {
                return response()->json(['error',"Order not found or expired"]);
            }
            $goods = [];

            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                $quantity = $item->quantity;

                $stock = Stock::findOrFail($product->stock_id);
                if ($quantity > $stock->quantity) {
                    return back()->with('error',"Sorry! You can't sell " . $quantity . " " . $product->unit . " of " . $product->name . ' - ' . $product->volume . ' ' . $product->measure . ". Quantity remained is " . $stock->quantity . " " . $product->unit);
                }
            }

            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                $quantity = $item->quantity;

                $stock = Stock::find($product->stock_id);

                // dd(Auth::user()->name);
                $attributes = [
                    'name' => $product->name,
                    'type' => $product->type,
                    'volume' => $product->volume,
                    'price' => $item->price * $quantity,
                    'quantity' => $quantity,
                    'unit' => $product->unit,
                    'seller' => Auth::user()->name,
                    'product_id' => $product->id,
                    'user_id' => Auth::user()->id,
                    'date' => Carbon::now('GMT+3')->toDateString(),
                    'stock_id' => $product->stock_id,
                    'sale_id' => 0,

                ];
                $good = Good::create($attributes);
                $stock->goods()->save($good);
                $goods[] = $good;

                $newQuantity = $stock->quantity - $quantity;
                $stock->update([
                    'quantity' => $newQuantity,
                ]);
                $stock->save();
            }
            $totalAmount = 0;
            foreach ($goods as $good) {
                $totalAmount = $totalAmount + $good->price;
            }
            $attribute = [
                'user_id' => Auth::user()->id,
                'seller' => Auth::user()->name,
                'customer_id' => 0,
                'receipt_number' => $request->receipt_number ?? "",
                'amount_paid' => $totalAmount,
                'date' => Carbon::now('GMT+3')->toDateString(),
            ];
            $sale = Sale::create($attribute);
            $at = ['sale_id' => $sale->id];
            foreach ($goods as $good) {
                $good->update($at);
                $sale->goods()->save($good);
            }
                $customer = Customer::find($order->customer_id);
                $atr = ['customer_id' => $customer->id];
                $sale->update($atr);
                $customer->sales()->save($sale);

                $order->status = 1;
                $order->served_date = Carbon::now('GMT+3')->toDateString();
                $order->served_by = Auth::user()->name;
                $order->save();
                // MESSAGE CONTENT
                $heading  = "Ndugu mteja,\nUmenunua bidhaa zifuatazo kutoka kwetu\n";
                $sales = [];
                foreach ($goods as $key => $purchasedGood) {
                    $sales[] = ++$key . ". " . $purchasedGood->name . " " . $purchasedGood->volume . " " . $purchasedGood->measure . " - " . $purchasedGood->quantity . " " . $purchasedGood->unit . "\n";
                }
                $totalCost = "Zinazogharimu Jumla ya TZS " . number_format($totalAmount, 0, '.', ',') . ".\n";
                $closing  = "Ahsante na karibu tena.";
                $messageBody = $heading . implode('', $sales) . $totalCost . $closing;

                $messagingService = new MessagingService();
                $sendMessageResponse = $messagingService->sendMessage($customer->phone, $messageBody);

                if ($sendMessageResponse == "Sent") {
                    if (REQ::is('api/*'))
                        return response()->json(['status'=>true,'message' => 'Order served successful'],200);
                    return back()->with('success','Order successful sold');
                } else {
                    if (REQ::is('api/*'))
                    return response()->json(['status'=>true, 'message' => 'Order successful sold but message not sent, crosscheck your inputs'],200);
                    return back()->with('error','Order successful sold but message not sent, crosscheck your inputs');
                }

            } catch (\Throwable $th) {
            if (REQ::is('api/*'))
            return response()->json([
                'status'=>false,
                'message' => $th], 500);
           return back()->with('error',$th->getMessage());
        }

}
    public function allSales(Request $request)
    {
        if (REQ::is('api/*')) {
            $sales=Sale::all();
            return response()->json([
                'sales'=>$sales,
                'status'=>true,
            ]);
        }
        $filteredStockId = $request->get('filteredStockId', "All Products");
        $filteredDate = $request->get('filteredDate', "All Days");

        if ($filteredDate == null) {
            $filteredDate = "All Days";
        }

        $goods = Good::latest()->get();
        if ($filteredDate != "All Days" && $filteredStockId != "All Products") {
            $goods = Good::where(['stock_id' => $filteredStockId, 'date' => $filteredDate])->latest()->get();
        } elseif ($filteredStockId != "All Products") {
            $goods = Good::where(['stock_id' => $filteredStockId])->latest()->get();
        } elseif ($filteredDate != "All Days") {
            $goods = Good::where('date', $filteredDate)->latest()->get();
        }

        $sales = Sale::latest()->get();
        if ($filteredDate != "All Days") {
            $sales = Sale::where('date', $filteredDate)->latest()->get();
        }

        $stocks = Stock::where('status', 1)->where('quantity', '>', 0)->get();
        $allStocks = Stock::all();
        $products = Product::where(['status' => 1])->get();

        $selectedDate = $filteredDate;
        return view('sales.index', compact(
            'goods',
            'stocks',
            'allStocks',
            'products',
            'filteredDate',
            'filteredStockId',
            'selectedDate',
            'sales'
        ));
    }
}
