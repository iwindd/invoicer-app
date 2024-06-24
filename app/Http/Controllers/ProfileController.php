<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    public function index() {
        return view("profile.index");
    }

    public function update(UpdateProfileRequest $request) {
        $this->auth()->update($request->validated());

        return Response()->noContent();
    }

    public function patch(PatchProfileRequest $request) {
        $this->auth()->update([
            'password' => Hash::make($request->safe()->only("password")['password'])
        ]); 

        return Response()->noContent();
    }
}
