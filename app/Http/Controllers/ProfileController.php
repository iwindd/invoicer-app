<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

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
}
