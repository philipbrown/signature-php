<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\Exceptions\SignatureVersionException;

class CheckVersion implements Guard
{
    /**
     * Check to ensure the auth parameters
     * satisfy the rule of the guard
     *
     * @param array $auth
     * @param array $signature
     * @return bool
     */
    public function check(array $auth, array $signature)
    {
        if (! isset($auth['auth_version'])) {
            throw new SignatureVersionException('The version has not been set');
        }

        if ($auth['auth_version'] !== $signature['auth_version']) {
            throw new SignatureVersionException('The signature version is not correct');
        }

        return true;
    }
}
