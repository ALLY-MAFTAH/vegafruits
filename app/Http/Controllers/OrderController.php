<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\NotificationService;
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
    public function getNewOrdersApi()
    {
        try {

            $orders = Order::where('status',false)->with('items')->with('customer')->latest()->get();

            return response()->json([
                'orders' => $orders,
                'status' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'orders' => $th->getMessage(),
                'status' => false
            ], 201);
        }
    }

    public function showOrder(Request $request, Order $order)
    {
        return view('orders.show', compact('order'));
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

            if ($sendMessageResponse == "Sent"&&session('cart')) {
                session(['verifyOTPDialog' => true]);
                session(['lastFourDigits' => $lastFourDigits]);

                return back();
            } else {
                return back()->with('error', 'OTP not sent, crosscheck your inputs');
            }
        } catch (\Throwable $th) {

            return back()->with('error', $th->getMessage());
        }
    }
    public function resendOTP()
    {
        try {

            $mobile = session('mobile');

            $otp = mt_rand(1000, 9999);
            session(['otp' => $otp]);

            $messageBody = "Your Vega Fruits Verification Code is: " . $otp;

            $messagingService = new MessagingService();
            $sendMessageResponse = $messagingService->sendMessage($mobile, $messageBody);

            $lastFourDigits = substr($mobile, -4);

            if ($sendMessageResponse == "Sent"&& session('cart')) {
                session(['verifyOTPDialog' => true]);
                session(['lastFourDigits' => $lastFourDigits]);

                return back();
            } else {
                return back()->with('error', 'OTP not sent, crosscheck your inputs');
            }
        } catch (\Throwable $th) {

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
                        $notificationRequest = new Request(['title' => "New Order :: " . $order->number, 'body' => "Please check your order list, new order has been created by ".$order->customer->name. " [ ".$order->customer->mobile." ]"]);
                        $notificationService =  new NotificationService();
                        $notificationResponse =  $notificationService->sendNotification($notificationRequest);

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
            $customerRequest = new Request( [
                "name" => session()->get('name'),
                "mobile" => session()->get('mobile'),

            ]);
            $customerController=new CustomerController();
            $customer = $customerController->postCustomer($customerRequest);

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

    public function markOrderAsPaid(Request $request, $orderId)
    {
    try {
        $order = Order::findOrFail($orderId);
        $order->is_paid = $request->input('is_paid');
        $order->save();

        return response()->json(['message' => 'Order marked as paid successfully']);
    } catch (\Exception $e) {

        return response()->json(['message' => $e], 500);
    }
    }
    public function markOrderAsContacted(Request $request, $orderId)
    {
    try {
        $order = Order::findOrFail($orderId);
        $order->was_contacted = $request->input('was_contacted');
        $order->save();

        return response()->json(['message' => 'Customer contacted']);
    } catch (\Exception $e) {

        return response()->json(['message' => $e], 500);
    }
    }


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
