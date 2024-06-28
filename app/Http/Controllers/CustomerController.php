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
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(
                    $this->auth()->customers()->with("invoices:customer_id,status,start,end")->select(["id", "joined_at", "firstname", "lastname",])
                )
                ->addColumn("action", "customers.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('customers.index');
    }
    
    /**
     * selectize
     *
     * @return void
     */
    public function selectize()
    {
        if (request()->ajax()) {
            $customers = $this->auth()->customers()->get(['id', 'firstname', 'lastname']);
            return Response()->json($customers);
        }

        return view('customers.index');
    }
    
    /**
     * application
     *
     * @return void
     */
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
    
    /**
     * get
     *
     * @param  mixed $request
     * @return void
     */
    public function get(Request $request)
    {
        $customer = $this->auth()->customers()->with('application')->find($request->id);
        $invoices = $customer->invoices()->get(['status', 'end']);

        return view("customers.customer.index", compact('customer', 'invoices'));
    }
    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->auth()->customers();
        $customer->create($request->validated());
        $this->activity('customer-create', $request->validated());

        return response()->noContent();
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(UpdateCustomerRequest $request)
    {
        $customer = $this->auth()->customers()->find($request->id);
        $customer->update($request->validated());
        if ($customer->application){
            $data = $request->validated();
            $customer->application->update(array_merge($request->safe()->only(['email']), ['name' => $data['firstname'] . " " . $data['lastname']]));
        }
        $this->activity('customer-update', $request->validated());

        return response()->noContent();
    }
    
    /**
     * destroy
     *
     * @param  mixed $request
     * @return void
     */
    public function destroy(Request $request)
    {
        $customer = $this->auth()->customers()->find($request->id);
        $customer->delete();

        $this->activity('customer-delete', $customer->attributesToArray());
        return response()->noContent();
    }
}
