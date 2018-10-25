<?php

namespace Api\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Api\Services;

class Controller extends BaseController
{

    public function __construct () {
        $this->TokenService = new Services\TokenService;
        $this->PublicKeyService = new Services\PublicKeyService;
        $this->error = app()->make('error');
        $this->input = app('request')->all();
    }

}
