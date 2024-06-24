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
            return datatables()->of($this->auth()->customers()->select('*'))
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
            $customers = $this->auth()->customers()->get(['id', 'firstname', 'lastname']);
            return Response()->json($customers);
        }

        return view('customers.index');
    }

    public function application()
    {
        if (request()->ajax()) {
            $customers = $this->auth()->customers()
                ->whereNull('application_id')
                ->select('id', 'firstname', 'lastname')
                ->get();
                
            return Response()->json($customers);
        }

        return view('customers.index');
    }

    public function get(Request $request)
    {
        $customer = $this->auth()->customers()->find($request->id);
        $invoices = $customer->invoices()->get(['status', 'end']);

        return view("customers.customer.index", compact('customer', 'invoices'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->auth()->customers();
        $customer->create($request->validated());

        return response()->noContent();
    }

    public function update(UpdateCustomerRequest $request)
    {
        $customer = $this->auth()->customers()->find($request->id);
        $customer->update($request->validated());

        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $customer = $this->auth()->customers()->find($request->id);
        $customer->delete();

        return response()->noContent();
    }
}
