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
*/

namespace App\Http\Middleware\Access;

use Closure;

use Api\Models;
use Api\Services;

class External
{

    private $error;

    public function __construct()
    {
        $this->error = app()->make('error');
        $this->TokenService = new Services\TokenService;
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
    public function handle($request, Closure $next, $guard = null)
    {

        $this->token();

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

    }


    /**
     * Pull Token and Make Sure Token is Valid
     *
     * @return mixed
     */
    public function token()
    {
        $this->error->required(['token']);
        $this->TokenService->get();

        return true;
    }
}