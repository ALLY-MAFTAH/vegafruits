<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StockController extends Controller
{


    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stocks = Stock::orderBy('type', 'DESC')->get();

        return view('stocks.index', compact('stocks'));
    }
    public function showStock(Request $request, Stock $stock)
    {

        return view('stocks.show', compact('stock'));
    }
    public function postStock(Request $request)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required |unique:stocks,name,except,id',
                'volume' => 'required',
                'unit' => 'required',
                'type' => 'required',
                'buying_price' => 'required',
                'quantity' => 'required',
            ]);

            $attributes['status'] = true;
            $stock = Stock::create($attributes);
        } catch (QueryException $th) {

            return back()->with(['error' => $th->getMessage()]);
        }

        $productController = new ProductController();
        $productResponse = $productController->postProduct($request, $stock);
        if ($productResponse['has_error']) {
            return back()->with(['error' => $productResponse['data']]);
        } else {

            $stock->product()->save($productResponse['data']);
        }


        return back()->with(['success' => "Product successful added to stock"]);
    }
    public function putStock(Request $request, Stock $stock)
    {
        // dd($request->all);
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'unit' => 'required',
                'type' => 'required',
                'buying_price' => 'required',
                'quantity' => 'required',
            ]);

            $stock->update($attributes);
        } catch (QueryException $th) {
            return back()->with('error', $th->getMessage());
        }
        $request->request->add(['price' => $request->price, 'has_discount' => $request->has_discount, 'stock_id' => $stock->id]); //add request

        $product = new ProductController();
        $product->putProduct($request, $stock->product);

        return back()->with('success', "Product edited successful");
    }
    public function toggleStatus(Request $request, Stock $stock)
    {

        $attributes = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $stock->update($attributes);

        return back()->with('success', "Stock status updated successful");
    }
    public function deleteStock(Stock $stock)
    {
        $stock->delete();

        $product = new ProductController();
        $product->deleteProduct($stock->product);

        return back()->with('success', "Stock deleted successful");
    }
}
