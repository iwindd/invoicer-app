<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
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

    public function selectize()
    {
        if (request()->ajax()) {
            $customers = Customer::where('application', Auth::user()->application)->get(['id', 'firstname', 'lastname']);
            return Response()->json($customers);
        }

        return view('customers.index');
    }

    public function get(Request $request)
    {
        $customer = Customer::find($request->id);
        $invoices = $customer->invoices()->get(['status', 'end']);

        return view("customers.customer.index", compact('customer', 'invoices'));
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

    public function update(UpdateCustomerRequest $request)
    {
        $data = $request->validated();

        try {
            Customer::where([
                ['id', $request->id],
                ['application', Auth::user()->application]
            ])->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'joinedAt' => $data['joinedAt'],
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
