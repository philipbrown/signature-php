<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Signature;

interface Guard
{
    /**
     * Check to ensure the auth parameters
     * satisfy the rule of the guard
     *
     * @param array $auth
     * @param array $signature
     * @return bool
     */
    public function check(array $auth, array $signature);
}
