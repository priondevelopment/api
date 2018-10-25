<?php

namespace Api\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

use Api\Models;
use Api\Token;
use Api\Traits;

class PublicKeyService
{
    use Traits\ApiTraits;

    /**
     * Find Public Key, Check for Block
     *
     */
    public function get($public_key='')
    {
        $public_key = $public_key ?: $this->input['public_key'];
        $brute = $this->blocked($public_key);

        try {
            $credentials = Models\ApiCredential::
                where('public_key', $public_key)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $brute->attempt($public_key);
            $this->error->code('2001');
        }

        return $credentials;
    }

    /**
     * Check if the Public Key is Blocked. Throws a Json Exception
     *
     * @return mixed
     */
    protected function blocked($public_key)
    {
        $public_key = $public_key ?: $this->input['public_key'];
        $brute = $this->brute->get('block')
            ->reset($this->brutePrefix, $this->brutePublicKey);
        $brute->check($public_key);

        return $brute;
    }


}
