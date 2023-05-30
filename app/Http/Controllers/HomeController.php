<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function welcome()
    {
        // $sendMessageResponse = "Seont";
        // $lastFourDigits = "0000";
        $stocks = Stock::where('status', true)->get();
        $products = Product::where('status', true)->get();
        return view('welcome', compact('stocks', 'products'));
    }
}
