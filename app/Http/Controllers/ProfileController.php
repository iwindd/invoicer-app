<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Phattarachai\LineNotify\Facade\Line;

class ProfileController extends Controller
{
    //
    public function index() {
        return view("profile.index");
    }

    public function update(UpdateProfileRequest $request) {
        $this->auth()->update($request->validated());
        $this->activity("profile-update", $request->validated());

        return Response()->json($request->validated());
    }

    public function patch(PatchProfileRequest $request) {
        $this->auth()->update([
            'password' => Hash::make($request->safe()->only("password")['password'])
        ]); 
        $this->activity("profile-password-patch", []);
        
        return Response()->noContent();
    }
}
