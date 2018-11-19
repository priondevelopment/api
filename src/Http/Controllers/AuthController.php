<?php

namespace Api\Http\Controllers;

/*
|---------------------------------------------------------------
| Authenticate an API Session
|---------------------------------------------------------------
|
|
*/

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Api\Models;

class AuthController extends Controller
{
    protected $data = [];

    public function __construct ()
    {
        parent::__construct();

        $this->data = [
            'status' => 1,
            'error' => 0,
        ];
    }


    /**
     * Create Initial Auth Token
     *
     * @route /api/auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAuth()
    {
        $this->error->required(['public_key']);
        $credentials = $this->PublicKeyService->get();

        $token = $this->TokenService->createInitial($credentials);
        $this->data['auth_token'] = $token->token;
        return response()->json($this->data);
    }


    /**
     * Validate a One Time Token
     *
     * @route /api/once
     * @public_key is the APIs Public Key. In this method, it's only used for brute force
     * @Token is the One Time Use Token
     * @Secret is a Hashed Key (Combination of Token & Secret)
     * @Refresh - Should we return a refresh token?
     */
    public function postOnce()
    {
        $this->error->required([
            'public_key',
            'token',
            'secret',
        ]);

        $token = $this->TokenService->get($this->input['token'], 'initial');
        $token->compareHash($this->input['secret']);
        $token->deactivate();

        $this->data['token'] = $this->TokenService->createToken($token->credentials);

        if (isset($this->input['refresh']) AND $this->input['refresh']) {
            $this->data['refresh'] = $this->TokenService->createRefresh($token->credentials);
        }
        return response()->json($this->data);
    }


    /**
     * Single Step Authentication (Only Requires Public/Private Keys)
     *
     * This Auth method only requires a public and private key
     */
    public function postSingle()
    {
        // Check if single auth method is active
        if (config('prionapi.auth_method') !== 'single') {
            $this->error->message('This auth method is not available');
        }

        $this->error->required(['public_key']);
        $credentials = $this->PublicKeyService->get();
        $this->data['token'] = $this->TokenService->createToken($credentials);

        return response()->json($this->data);
    }



    /**
     * Create a New Token from a Refresh Token
     *
     */
    public function postRefresh()
    {
        $this->error->required([
            'token',
            'public_key',
        ]);
        $credentials = $this->PublicKeyService->get();
        $token = $this->TokenService->get($this->input['token'], 'refresh');

        if ($token->api_credential_id != $credentials->id) {
            $this->error->message('Refresh token is not valid.');
        }
        $token->deactivate();

        $this->data['token'] = $this->TokenService->createToken($credentials);
        $this->data['refresh'] = $this->TokenService->createRefresh($credentials);

        return response()->json($this->data);
    }


    /**
     * This is ONLY Available Locally (Test Environments)
     *
     * Find the hash for a given token for testing
     */
    public function postHash()
    {
        $environment = app()->environment();
        if ($environment != 'local') {
            $this->error->code(404);
        }

        $this->error->required(['token']);
        $token = $this->TokenService->get($this->input['token'], 'once');
        $this->data['message'] = $token->hash;

        return response()->json($this->data);
    }

}