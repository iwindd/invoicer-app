<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    //
    public function index()
    {
        if (request()->ajax()){
            return datatables()->of(Customer::with('owner')->where('application', Auth::user()->application)->select('*'))
                    ->addColumn("action", "customers.action")
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
        }

        return view('customers.index');
    }

    public function get(Request $request) {
        $customer = Customer::find($request->id);

        return view("customers.customer.index", compact('customer'));
    }

    public function store(Request $request) {
        $request->validate([
            'firstname' => ['required', 'min:5', 'max:255'],
            'lastname' => ['required', 'min:5', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:customers', 'unique:users'],
            'joinedAt' => ['required']
        ]);

        $customer = new Customer;
        $customer ->application = Auth::user()->application;
        $customer ->firstname = $request->firstname;
        $customer ->lastname = $request->lastname;
        $customer ->email = $request->email;
        $customer ->joinedAt = $request->joinedAt;
        $customer ->createdBy_id = Auth::user()->id;
        $customer->save();

        return Response()->json($customer);
    }

    public function destroy(Request $request) {
        try {
            $customer = Customer::where([
                ['id', $request->id],
                ['application', Auth::user()->application]
            ]);

            $customer->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
