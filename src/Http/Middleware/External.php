<?php

/*
|---------------------------------------------------------------
|   External API Middleware
|---------------------------------------------------------------
|
|   This handles API requests from external sources. It is
|   setup by the user and the API credentials used are:
|    - internal != 1
|
|   APIs with Only External Access are available from Prion Platform
|   clients and from external user products. All external
|   requests need to be rate limited.
|
*/

namespace Api\Http\Middleware;

use Closure;

use Api\Models;
use Exception;

class External extends ValidationMiddleware
{

    public function __construct()
    {
        $this->error = app()->make('error');
    }

    /**
     * Request Requires a Valid API Connection. User is not required
     * to be logged into account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permissions = array_slice(func_get_args(), 2);
        $token = $this->token();
        $this->permissions($token, $permissions);

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }
}