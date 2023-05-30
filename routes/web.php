<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/cart/get-cart', [CartController::class, 'getCart']);

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/checkout', [CartController::class, 'cartCheckout'])->name('cart.checkout');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/empty', [CartController::class, 'emptyCart'])->name('cart.empty');
Route::post('/add-customer_info', [OrderController::class, 'customerInfo'])->name('orders.customer_info');
Route::post('/verify-OTP', [OrderController::class, 'verifyOTP'])->name('orders.verify_OTP');
Route::get('/payments', [OrderController::class, 'payments'])->name('payments');


Auth::routes();


Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // CUSTOMERS ROUTES
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/add-customer', [CustomerController::class, 'postCustomer'])->name('customers.add');
    Route::get('/show-customer/{customer}', [CustomerController::class, 'showCustomer'])->name('customers.show');
    Route::put('/edit-customer/{customer}', [CustomerController::class, 'putCustomer'])->name('customers.edit');
    Route::delete('/delete-customer/{customer}', [CustomerController::class, 'deleteCustomer'])->name('customers.delete');
    Route::put('customers/{customer}/status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

    // ORDERS ROUTES
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/add-order', [OrderController::class, 'postOrder'])->name('orders.add');
    Route::post('/send-order', [OrderController::class, 'sendOrder'])->name('orders.send');
    Route::get('/show-order/{order}', [OrderController::class, 'showOrder'])->name('orders.show');
    Route::put('/edit-order/{order}', [OrderController::class, 'putOrder'])->name('orders.edit');
    Route::delete('/delete-order/{order}', [OrderController::class, 'deleteOrder'])->name('orders.delete');

    // PRODUCTS ROUTES
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/add-product', [ProductController::class, 'postProduct'])->name('products.add');
    Route::get('/show-product/{product}', [ProductController::class, 'showProduct'])->name('products.show');
    Route::put('/edit-product/{product}', [ProductController::class, 'putProduct'])->name('products.edit');
    Route::delete('/delete-product/{product}', [ProductController::class, 'deleteProduct'])->name('products.delete');
    Route::put('products/{product}/status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::put('products/{product}/discount', [ProductController::class, 'toggleDiscount'])->name('products.toggle-discount');

    // STOCK ROUTES
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/show-stock/{stock}', [StockController::class, 'showStock'])->name('stocks.show');
    Route::put('stocks/{stock}/status', [StockController::class, 'toggleStatus'])->name('stocks.toggle-status');
    Route::post('/add-stock', [StockController::class, 'postStock'])->name('stocks.add');
    Route::put('/edit-stock/{stock}', [StockController::class, 'putStock'])->name('stocks.edit');
    Route::delete('/delete-stock/{stock}', [StockController::class, 'deleteStock'])->name('stocks.delete');

    // SALES ROUTES
    Route::get('/sales', [SaleController::class, 'allSales'])->name('sales.index');
});
