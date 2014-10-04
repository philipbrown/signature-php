<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\SignatureException;

class CheckHash implements Guard
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
        if (! isset($auth['hash'])) {
            throw new SignatureException('The signature has not been set');
        }

        if ($auth['hash'] !== $signature->sign()['auth']['hash']) {
            throw new SignatureException('The signature is not invalid');
        }

        return true;
    }
}
