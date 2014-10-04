<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\SignatureException;

class CheckKey implements Guard
{
    /**
     * Check to ensure the auth parameters
     * satisfy the rule of the guard
     *
     * @param array $auth
     * @param Signature $signature
     * @return bool
     */
    public function check(array $auth, Signature $signature)
    {
        if (! isset($auth['key'])) {
            throw new SignatureException('The authentication key has not been set');
        }

        if ($auth['key'] !== $signature->auth()['key']) {
            throw new SignatureException('The authentication key is not valid');
        }

        return true;
    }
}
