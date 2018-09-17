<?php

namespace PrionApiauth;

/**
 * This file is part of Prion Development's Apiauth,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Laratrust
 */

use Illuminate\Support\Facades\Facade;

class PrionApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'prionapiauth';
    }
}