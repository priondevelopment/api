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

class External
{

    private $error;

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
    public function handle($request, Closure $next, $permissions)
    {

        $token = $this->token();
        if (!$token->credentials->internal) {
            $this->error->code('2021');
        }

        $this->permissions($token, $permissions);

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
        $this->error->required(['token','hash']);
        $input = app('input')->only(['token','hash']);

        try {
            $token = Models\Api\Token::
                where('token', $input['token'])
                ->findOrFail();
        } catch (Exception $e) {
            $this->error->code('2010');
        }
        $token->compareHash($input['hash']);

        return $token;
    }


    /**
     * Check if Permissions are Valid
     *
     * @param $token
     */
    public function permissions($token, $permissions)
    {
        $credentialPermission = $token->credential->permission_slugs;
        $permissions = collect($permissions);
        $compare = $credentialPermission->intersect($permissions);

        if ($compare->count()) {
            return true;
        }

        $this->error->code('2020');
    }
}