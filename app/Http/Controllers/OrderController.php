<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Routing\Controller;

use App\Models\Item;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Services\MessagingService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::latest()->get();
        $stocks = Stock::latest()->get();

        return view('orders.index', compact('orders', 'stocks'));
    }

    public function showOrder(Request $request, Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function sendOrder(Request $request)
    {
        $selectedStocks = json_decode($request->selectedStocks, true);

        try {

            $attributes = [
                'number' => Carbon::now(),
                'served_date' => null,
                'status' => false,
                'created_by' => Auth::user()->name,
                'user_id' => Auth::user()->id,
            ];

            $order = Order::create($attributes);
            $user = User::find(Auth::user()->id);
            $user->orders()->save($order);

            foreach ($selectedStocks as $stock) {
                $itemAttributes = [
                    'stock_id' => $stock['stock_id'],
                    'name' => $stock['name'],
                    'quantity' => $stock['quantity'],
                    'volume' => $stock['volume'],
                    'measure' => $stock['measure'],
                    'unit' => $stock['unit'],
                    'order_id' => $order->id,
                ];

                $item = Item::create($itemAttributes);
                $order->items()->save($item);
            }

            $receivers = [
                setting('Admin Email'),
                setting('Afya Email'),

            ];
            foreach ($receivers as $receiver) {

                Mail::send('mails.order', ['order' => $selectedStocks], function ($m) use ($receiver) {
                    $m->from('amelipaapp@gmail.com', setting('App Name', "Tanga Watercom"));

                    $m->to($receiver)->subject('Please, receive new order.');
                });
            }


            notify()->success('You have successful sent an order');
            return redirect()->back();
        } catch (QueryException $th) {
            dd($th->getMessage());
            notify()->error($th->getMessage());
            return back()->with('error', 'Failed to create order');
        }
    }
    public function postOrder(Request $request)
    {
        $itemsIds = $request->input('ids');
        $itemsQuantities = $request->input('quantities');

        $selectedStocks = [];
        for ($i = 0; $i < count($itemsIds); $i++) {
            $stock = Stock::findOrFail($itemsIds[$i]);
            $selectedStock = [
                'stock_id' => $stock->id,
                'name' => $stock->name,
                'volume' => $stock->volume,
                'measure' => $stock->measure,
                'unit' => $stock->unit,
                'quantity' => $itemsQuantities[$i],
            ];

            array_push($selectedStocks, $selectedStock);
        }
        return response()->json($selectedStocks);
    }

    public function customerInfo(Request $request)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'mobile' => 'required',
                'delivery_location' => 'required',
                'delivery_date' => 'required',
                'delivery_time' => 'required',
            ]);

            session(['name' => $request->name]);
            session(['mobile' => $request->mobile]);
            session(['delivery_date' => $request->delivery_date]);
            session(['delivery_time' => $request->delivery_time]);
            session(['delivery_location' => $request->delivery_location]);

            $otp = mt_rand(1000, 9999);
            session(['otp' => $otp]);

            $messageBody = "Your Vega Fruits Verification Code is: " . $otp;

            $messagingService = new MessagingService();
            $sendMessageResponse = $messagingService->sendMessage($request->mobile, $messageBody);
            // $sendMessageResponse = "Sent";
            $lastFourDigits = substr($request->mobile, -4);

            if ($sendMessageResponse == "Sent") {
                session(['verifyOTPDialog' => true]);
                session(['lastFourDigits' => $lastFourDigits]);

                return back();
            } else {
                return back()->with('error', 'OTP not sent, crosscheck your inputs');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }
    public function verifyOTP(Request $request)
    {
        if (!session('otp')) {

            return Redirect::route('welcome');

        } else {

            $otp = $request->first . $request->second . $request->third . $request->fourth;

            try {

                $storedOtp = session('otp');

                if (intval($otp) == $storedOtp) {
                    session()->forget('otp');
                    session()->forget('verifyOTPDialog');
                    $orderResponse = self::createOrder();

                    if ($orderResponse['status'] == "Sent") {
                        $order = $orderResponse['data'];
                        return Redirect::route('payments', $order)->with(['success' => 'Order created successfully']);
                    } else {
                        return back()->with('error', $orderResponse['data']);
                    }
                } else {
                    return back()->with('error', 'OTP Verification failed, please try again');
                }
            } catch (\Throwable $th) {
                return back()->with('error', $th->getMessage());
            }
        }
    }
    public function createOrder()
    {
        $carts = session()->get('cart');

        try {
            $customerAttr = [
                "name" => session()->get('name'),
                "mobile" => session()->get('mobile'),

            ];
            $customer = Customer::create($customerAttr);

            $lastOrderRow = Order::latest('created_at')->latest('id')->first();
            $lastOrderNumber = $lastOrderRow ? $lastOrderRow->number : "VFO/00000";
            $newOrderNumber = ++$lastOrderNumber;

            $orderAttr = [
                "number" => $newOrderNumber,
                "delivery_date" => session()->get('delivery_date'),
                "delivery_time" => session()->get('delivery_time'),
                "delivery_location" => session()->get('delivery_location'),
                'date' => Carbon::now('GMT+3')->toDateString(),
                'customer_id' => $customer->id
            ];
            $order = Order::create($orderAttr);

            foreach ($carts as $cart) {
                $product = Product::find($cart['id']);
                $quantity = $cart['quantity'];

                $attributes = [
                    'name' => $product->name,
                    'type' => $product->type,
                    'volume' => $product->volume,
                    'price' => $cart['price'] * ($quantity / $product->volume),
                    'quantity' => $quantity,
                    'unit' => $product->unit,
                    'product_id' => $product->id,
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'stock_id' => $product->stock_id,
                ];
                $item = Item::create($attributes);
                $order->items()->save($item);
            }
            $totalAmount = 0;
            foreach ($order->items as $item) {
                $totalAmount = $totalAmount + $item->price;
            }

            $order->update(['total_amount' => $totalAmount]);
            session()->forget('cart');
            session(['paymentModal' => true]);

            return ['status' => true, 'data' => $order];
        } catch (\Throwable $th) {
            return ['status' => false, 'data' => $th->getMessage()];
        }
    }

    // public function putOrder(Request $request, Order $order)
    // {

    //     try {
    //         $attributes = $request->validate([
    //             'volume' => 'required',
    //             'unit' => 'required',
    //             'measure' => 'required',
    //             'price' => 'required',
    //         ]);

    //         $order->update($attributes);
    //     } catch (QueryException $th) {
    //         notify()->error('Order "' . $request->name . '" with volume of "' . $request->quantity . '" already exists.');
    //         return back();
    //     }
    //     notify()->success('You have successful edited an order');
    //     return redirect()->back();
    // }

    // public function toggleStatus(Request $request, Order $order)
    // {
    //     try {

    //         $attributes = $request->validate([
    //             'status' => ['required', 'boolean'],
    //         ]);
    //         $attributes['served_date'] =  Carbon::now();

    //         $order->update($attributes);

    //         $items = Item::where(['order_id' => $order->id])->get();
    //         foreach ($items as $item) {
    //             $stock = Stock::findOrFail($item->stock_id);

    //             $newQuantity = $stock->quantity + $item->quantity;
    //             $attributes = [
    //                 'quantity' => $newQuantity
    //             ];
    //             $stock->update($attributes);
    //         }
    //     } catch (QueryException $th) {
    //         notify()->error($th->getMessage());
    //         return back();
    //     }
    //     notify()->success('You have successfully updated order status');
    //     return back();
    // }

    public function deleteOrder(Order $order)
    {

        $order->delete();

        return back()->with('success', 'Order deleted successful');
    }
    public function payments(Request $request, Order $order)
    {
        return view('payments', compact('order'))->with('success', "Order created successful");
    }
}
