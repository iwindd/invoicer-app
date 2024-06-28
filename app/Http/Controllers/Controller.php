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
    
    /**
     * auth
     *
     * @return User
     */
    protected function auth() : User{
        return Auth::user();
    }
    
    /**
     * activity
     *
     * @param  mixed $name
     * @param  mixed $payload
     * @param  mixed $user
     * @param  mixed $notify
     * @return void
     */
    protected function activity($name, $payload = [], $user = null, $notify = true) {
        try {
            if (!$user) {
                $user = $this->auth();
            }

            if (!$user) return;

            LogActivity::dispatch($user, $name, $payload, $notify);
        } catch (\Exception $e) {
            //
        }
    }
}
