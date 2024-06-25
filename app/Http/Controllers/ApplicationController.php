<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAsRequest;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    //
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(
                    User::where("role", "application")
                )
                ->addColumn("action", "applications.action")
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('applications.index');
    }

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

        return Response()->noContent();
    }

    
    public function loginAs(LoginAsRequest $request) {
        Auth::loginUsingId($request->id);
        
        return Response()->noContent();
    }
}
