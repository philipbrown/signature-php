<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\SignatureException;

class CheckVersion implements Guard
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
        if (! isset($auth['version'])) {
            throw new SignatureException('The version has not been set');
        }

        if ($auth['version'] !== $signature->auth()['version']) {
            throw new SignatureException('The signature version is not correct');
        }

        return true;
    }
}
