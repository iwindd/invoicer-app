<?php

namespace App\Http\Controllers;

use App\Jobs\LogActivity;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function auth() : User{
        return Auth::user();
    }

    protected function activity($name, $payload = [], $notify = true) {
        try {
            $user = $this->auth();
            if (!$user) return;

            LogActivity::dispatch($user, $name, $payload, $notify);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
