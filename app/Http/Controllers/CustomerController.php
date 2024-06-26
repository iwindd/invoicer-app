<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
            $customers =  Cache::remember('selectize', 86400*30, function () {
                return $this->auth()->customers()->get(['id', 'firstname', 'lastname', 'application_id']);
            });
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
        $customer = $customer->create($request->validated());
        $this->activity('customer-create', $request->validated());

        // add to cache
        $selectize = Cache::get('selectize', []);
        $selectize[] = $customer->only(['id', 'firstname', 'lastname', 'application_id']);
        Cache::put('selectize', $selectize, 86400*30);

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

        // Update the cache
        $customers = collect(Cache::get('selectize', []));
        $updatedCustomers = $customers->map(function ($cachedCustomer) use ($customer) {
            if ($cachedCustomer['id'] == $customer->id) {
                $cachedCustomer['firstname'] = $customer->firstname;
                $cachedCustomer['lastname'] = $customer->lastname;
            }
            return $cachedCustomer;
        });

        Cache::put('selectize', $updatedCustomers->toArray(), 86400 * 30);

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

        // update cache
        $customers = collect(Cache::get('selectize', []));
        $updatedCustomers = $customers->reject(function ($cachedCustomer) use ($customer) {
            return $cachedCustomer['id'] == $customer->id;
        });
        Cache::put('selectize', $updatedCustomers->values()->toArray(), 86400 * 30);

        $this->activity('customer-delete', $customer->attributesToArray());
        return response()->noContent();
    }
}
