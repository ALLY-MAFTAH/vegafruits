<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::latest()->get();
        $stocks = Stock::latest()->get();

        return view('products.index', compact('products', 'stocks'));
    }

    // SHOW PRODUCT
    public function showProduct(Request $request, Product $product)
    {

        $stocks = Stock::where(['status' => 1])->get();
        $ingredients = $product->ingredients;

        return view('products.show', compact('product', 'ingredients', 'stocks'));
    }

    // POST PRODUCT
    public function postProduct(Request $request, Stock $stock)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'unit' => 'required',
                'volume' => 'required',
                'selling_price' => 'required',
                'type' => 'required',
            ]);

            if ($request->hasFile('photo')) {

                $photoPath = $request->file('photo')->storeAs(
                    '/images',
                    'img-' . $request->name . '.' . $request->file('photo')->getClientOriginalExtension(),
                    'public'
                );
            } else {
                return back()->with('error', "Please upload product photo");
            }
            $attributes['status'] = true;
            $attributes['photo'] = $photoPath;
            $attributes['stock_id'] = $stock->id;

            $product = Product::create($attributes);
        } catch (QueryException $th) {
            return ['has_error' => true, 'data' => $th->getMessage()];
        }

        return ['has_error' => false, 'data' => $product];
    }

    // EDIT PRODUCT
    public function putProduct(Request $request, Product $product)
    {
        $stock = Stock::findOrFail($request->stock_id);

        try {
            $attributes = $request->validate([
                'volume' => 'required',
                'unit' => 'required',
                'selling_price' => 'required',
                'type' => 'required',
            ]);

            $attributes['stock_id'] = $stock->id;
            $attributes['name'] = $stock->name;
            $attributes['has_discount'] = $request->has_discount;

            $product->update($attributes);
        } catch (QueryException $th) {
            return ['has_error' => true, 'data' => $th->getMessage()];
        }

        return ['has_error' => false, 'data' => $product];
    }

    // TOGGLE PRODUCT STATUS
    public function toggleStatus(Request $request, Product $product)
    {

        $attributes = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $product->update($attributes);


        return back()->with('success', 'You have successfully updated product status');
    }
    public function toggleDiscount(Request $request, Product $product)
    {
        // dd($product);
        $attributes = $request->validate([
            'has_discount' => ['required', 'boolean'],
        ]);

        $product->update($attributes);

        return back()->with('success', 'You have successfully updated product discount status');

    }

    // DELETE PRODUCT
    public function deleteProduct(Product $product)
    {

        $itsName = $product->name;
        $product->delete();


        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
