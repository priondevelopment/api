<?php

namespace Api\Contracts;

/**
 * This file is part of Setting,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

interface ApiInterface
{

    /**
     * Pull existing record or create a new record
     *
     * @param $key
     * @return mixed|Setting\Models\Setting
     */
    private function getOrCreate($key);


    /**
     * Retrieve a Setting Model Record
     *
     * @param $key
     * @return mixed
     */
    public function get($key);


    /**
     * Retrieve the Value for a Key
     *
     * @param $key
     * @return mixed
     */
    public function value($key);


    /**
     * Does this Key Exist?
     *
     * @param $key
     * @return bool
     */
    public function exists($key);


    private function checkValue($value);
}