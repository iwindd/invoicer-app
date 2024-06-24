<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    //
    public function index()
    {
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

        return Response()->noContent();
    }
}
