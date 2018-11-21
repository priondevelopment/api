<?php

namespace Api\Http\Middleware;

use Closure;

use Api\Models;
use Exception;

class ValidationMiddleware
{

    protected $error;

    public function __construct()
    {
        $this->error = app()->make('error');
    }

    /**
     * Pull Token and Make Sure Token is Valid
     *
     * @return mixed
     */
    public function token()
    {
        $this->error->required(['token','hash']);
        $input = app('request')->only(['token','hash']);

        try {
            $token = Models\Api\Token::
                where('token', $input['token'])
                ->firstOrFail();
        } catch (Exception $e) {
            $this->error->code('2010');
        }
        $token->valid;
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
        if (!count($permissions)) {
            return true;
        }

        $credentialPermission = $token->credential->permission_slugs;
        $permissions = collect($permissions);
        $compare = $credentialPermission->intersect($permissions);

        if (!$compare->count()) {
            $this->error->code('2020');
        }
    }
}