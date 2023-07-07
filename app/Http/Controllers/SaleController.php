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

    public function index(Request $request)
    {


        $filteredStockName = "";
        $filteredDate = Carbon::now('GMT+3')->toDateString();
        $selectedStockName = "";
        $selectedDate = "";

        $filteredStockName = $request->get('filteredStockName', "All Products");
        $filteredDate = $request->get('filteredDate', "All Days");

        if ($filteredDate == null) {
            $filteredDate = "All Days";
        }
        $filteredStock = Stock::where(['name' => $filteredStockName])->first();

        if ($filteredDate != "All Days" && $filteredStockName != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStock->id, 'date' => $filteredDate])->latest()->paginate(10);
        } elseif ($filteredDate == "All Days" && $filteredStockName != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStock->id])->latest()->paginate(10);
        } elseif ($filteredStockName == "All Products" && $filteredDate != "All Days") {
            $sales = Sale::where('date', $filteredDate)->latest()->paginate(10);
        } else {
            $sales = Sale::latest()->paginate(10);
        }
        $selectedStockName = $filteredStockName;
        $selectedDate = $filteredDate;

        $stocks = Stock::where('status', 1)->where('quantity', '>', 0)->get();
        $allStocks = Stock::all();
        $products = Product::where(['status' => 1])->get();

        return view('cart.index', compact('sales', 'products', 'stocks', 'allStocks', 'filteredDate', 'filteredStockName', 'selectedStockName', 'selectedDate'));
    }

    // SELL PRODUCT
    public function sellProduct(Request $request, $orderId)
    {
    try {
        $order = Order::findOrFail($orderId);

        if (!$order) {
            return response()->json(['error',"Order not found or expired expired"]);
        }
        $purchases = [];

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
                    'good_id' => 0,

                ];
                $sale = Sale::create($attributes);
                $stock->sales()->save($sale);
                $purchases[] = $sale;

                $newQuantity = $stock->quantity - $quantity;
                $stock->update([
                    'quantity' => $newQuantity,
                ]);
                $stock->save();
            }
            $totalAmount = 0;
            foreach ($purchases as $purchase) {
                $totalAmount = $totalAmount + $purchase->price;
            }
            $attribute = [
                'user_id' => Auth::user()->id,
                'seller' => Auth::user()->name,
                'customer_id' => 0,
                'receipt_number' => $request->receipt_number ?? "",
                'amount_paid' => $totalAmount,
                'date' => Carbon::now('GMT+3')->toDateString(),
            ];
            $good = Good::create($attribute);
            $at = ['good_id' => $good->id];
            foreach ($purchases as $purchase) {
                $purchase->update($at);
                $good->purchases()->save($purchase);
            }
                $customer = Customer::find($order->customer_id);
                $atr = ['customer_id' => $customer->id];
                $good->update($atr);
                $customer->goods()->save($good);

                $order->status = 1;
                $order->served_date = Carbon::now('GMT+3')->toDateString();
                $order->served_by = Auth::user()->name;
                $order->save();
                // MESSAGE CONTENT
                $heading  = "Ndugu mteja,\nUmenunua bidhaa zifuatazo kutoka kwetu\n";
                $boughtGoods = [];
                foreach ($purchases as $key => $purchasedGood) {
                    $boughtGoods[] = ++$key . ". " . $purchasedGood->name . " " . $purchasedGood->volume . " " . $purchasedGood->measure . " - " . $purchasedGood->quantity . " " . $purchasedGood->unit . "\n";
                }
                $totalCost = "Zinazogharimu Jumla ya TZS " . number_format($totalAmount, 0, '.', ',') . ".\n";
                $closing  = "Ahsante na karibu tena.";
                $messageBody = $heading . implode('', $boughtGoods) . $totalCost . $closing;

                $messagingService = new MessagingService();
                $sendMessageResponse = $messagingService->sendMessage($customer->phone, $messageBody);

                if ($sendMessageResponse == "Sent") {
                    return back()->with('success','Order successful sold');
                } else {
                    return back()->with('error','Order successful sold but message not sent, crosscheck your inputs');
                }

        } catch (\Throwable $th) {
           return back()->with('error',$th->getMessage());
        }

}



    public function allSales(Request $request)
    {
        if (REQ::is('api/*')) {
            $goods=Good::all();
            return response()->json([
                'goods'=>$goods,
                'status'=>true,
            ]);
        }
        $filteredStockId = $request->get('filteredStockId', "All Products");
        $filteredDate = $request->get('filteredDate', "All Days");

        if ($filteredDate == null) {
            $filteredDate = "All Days";
        }

        $sales = Sale::latest()->get();
        if ($filteredDate != "All Days" && $filteredStockId != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStockId, 'date' => $filteredDate])->latest()->get();
        } elseif ($filteredStockId != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStockId])->latest()->get();
        } elseif ($filteredDate != "All Days") {
            $sales = Sale::where('date', $filteredDate)->latest()->get();
        }

        $boughtGoods = Good::latest()->get();
        if ($filteredDate != "All Days") {
            $boughtGoods = Good::where('date', $filteredDate)->latest()->get();
        }

        $stocks = Stock::where('status', 1)->where('quantity', '>', 0)->get();
        $allStocks = Stock::all();
        $products = Product::where(['status' => 1])->get();

        // $selectedStockName = $filteredStockName;
        $selectedDate = $filteredDate;
        return view('sales.index', compact(
            'sales',
            'stocks',
            'allStocks',
            'products',
            'filteredDate',
            'filteredStockId',
            // 'selectedStockName',
            'selectedDate',
            'boughtGoods'
        ));
    }
}
