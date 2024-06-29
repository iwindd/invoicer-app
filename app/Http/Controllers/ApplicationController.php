<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAsRequest;
use App\Http\Requests\PatchApplicationRequest;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApplicationController extends Controller
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
                    User::where("role", "application")->with("customer:application_id,id")->select(["id", "created_at", "name", "status"])
                )
                ->addColumn("action", "applications.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('applications.index');
    }

        
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreApplicationRequest $request)
    {
        $customer = $this->auth()->customers()->find($request->id);
        $application = $customer->application()->create([
            'name' => ($customer->firstname)." ".($customer->lastname),
            'email' => $customer->email,
            'role' => "application",
            'password' => \bcrypt('password'),
        ]);
        $customer->update(['application_id' => $application->id]);
        $this->activity('application-create', $customer->attributesToArray());

        // Update the cache
        $customers = collect(Cache::get('selectize', []));
        $updatedCustomers = $customers->map(function ($cachedCustomer) use ($customer) {
            if ($cachedCustomer['id'] == $customer->id) {
                $cachedCustomer['application_id'] = $customer->application_id;
            }
            return $cachedCustomer;
        });
        
        Cache::put('selectize', $updatedCustomers->toArray(), 86400 * 30);

        return Response()->noContent();
    }

    public function patch(PatchApplicationRequest $request) {
        $application = Customer::find($request->id)->application;
        $application->update($request->safe()->only('status'));
        $this->activity('application-patch', $application->attributesToArray());
        return Response()->noContent();
    }

        
    /**
     * loginAs
     *
     * @param  mixed $request
     * @return void
     */
    public function loginAs(LoginAsRequest $request) {
        Auth::loginUsingId($request->id);
        
        return Response()->noContent();
    }
}
