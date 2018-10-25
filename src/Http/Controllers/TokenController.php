<?php

namespace Api\Http\Controllers;

/*
|---------------------------------------------------------------
| Authenticate an API Session
|---------------------------------------------------------------
|
|
 */

use Api\Models;

class TokenController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Check if a Token is Active
     *
     * @param $public_key
     * @param $token
     */
    public function getStatus($token)
    {
        $token = $this->TokenService->get($token);

        return response()->json([
            'status' => 1,
        ]);
    }

}