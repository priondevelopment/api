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
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Api\Models;
use Api\Services;
use Exception;

class Internal
{

    private $error;

    public function __construct(Services\BlockService $BlockService)
    {
        $this->error = app()->make('error');
        $this->input = app('input')->only([
            'token',
        ]);

        $this->BlockService = $BlockService;
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

        $token = $this->token();
        $this->internal($token);

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
        $this->error->required([
            'token',
        ]);

        $token = $this->BlockService->token($token);
        return $token;
    }


    /**
     * Internal Credential Check
     *
     * @param $token
     */
    public function internal($token)
    {
        if (!$token->credentials->internal) {
            $this->error->code('2012');
        }
    }

}