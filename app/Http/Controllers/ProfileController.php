<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Phattarachai\LineNotify\Facade\Line;

class ProfileController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index() {
        return view("profile.index");
    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(UpdateProfileRequest $request) {
        $guard = ['name'];
        if (Auth::user()->role == "application") $guard = [];
        $this->auth()->update($request->safe()->only(array_merge(['lineToken'], $guard)));
        $this->activity("profile-update", $request->validated());

        return Response()->json($request->validated());
    }
    
    /**
     * patch
     *
     * @param  mixed $request
     * @return void
     */
    public function patch(PatchProfileRequest $request) {
        $this->auth()->update([
            'password' => Hash::make($request->safe()->only("password")['password'])
        ]); 
        $this->activity("profile-password-patch", []);
        
        return Response()->noContent();
    }
}
