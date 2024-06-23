<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    //
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Customer::with('owner')->where('application', Auth::user()->application)->select('*'))
                ->addColumn("action", "customers.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('customers.index');
    }

    public function get(Request $request)
    {
        $customer = Customer::find($request->id);

        return view("customers.customer.index", compact('customer'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        $customer = new Customer;
        $customer->application = Auth::user()->application;
        $customer->firstname = $data['firstname'];
        $customer->lastname = $data['lastname'];
        $customer->email = $data['email'];
        $customer->joinedAt = $data['joinedAt'];
        $customer->createdBy_id = Auth::user()->id;
        $customer->save();

        return Response()->json($customer);
    }

    public function update(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'min:5', 'max:255'],
            'lastname' => ['required', 'min:5', 'max:255'],
            'email' => ['required', 'max:255', 'email', Rule::unique('customers')->ignore($request->id), Rule::unique('users')->ignore($request->id)],
            'joinedAt' => ['required']
        ]);

        try {
            Customer::where([
                ['id', $request->id],
                ['application', Auth::user()->application]
            ])->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'joinedAt' => $request->joinedAt,
            ]);

            return response()->noContent();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
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
