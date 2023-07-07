<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;


use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as REQ;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::all();

            if (REQ::is('api/*')) {
                return response()->json([
                    'customers'=>$customers,
                    'status'=>true,
                ]);
            }
            return view('customers.index', compact('customers'));

        } catch (\Throwable $th) {
            if (REQ::is('api/*')) {
                return response()->json([
                    'error'=>$th->getMessage(),
                    'status'=>false,
                ]);
            }
            return back()->with('error', $th->getMessage());

        }
    }

    // SHOW CUSTOMER
    public function showCustomer(Request $request, Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // POST CUSTOMER
    public function postCustomer(Request $request)
    {
        try {

            $customer=Customer::where('mobile',$request->mobile)->first();

            if (!$customer) {
                $attributes = $request->validate([
                    'name' => 'required',
                    'mobile' => 'required',
                ]);

                $customer = Customer::create($attributes);
            }

            return $customer;
        } catch (QueryException $th) {

            return back()->with('error', $th->getMessage());
        }
    }

    // EDIT CUSTOMER
    public function putCustomer(Request $request, Customer $customer)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'phone' => 'required',
            ]);

            $customer->update($attributes);
        } catch (QueryException $th) {
            return back();
        }
        return redirect()->back();
    }

    // TOGGLE CUSTOMER STATUS
    public function toggleStatus(Request $request, Customer $customer)
    {

        $attributes = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $customer->update($attributes);
        return back();
    }

    // DELETE CUSTOMER
    public function deleteCustomer(Customer $customer)
    {

        $itsName = $customer->name;
        $customer->delete();
        return back();
    }
}
