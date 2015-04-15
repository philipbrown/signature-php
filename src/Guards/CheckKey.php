<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Exceptions\SignatureKeyException;

class CheckKey implements Guard
{

    /**
     * Check to ensure the auth parameters
     * satisfy the rule of the guard
     *
     * @param array  $auth
     * @param array  $signature
     * @param string $prefix
     * @throws SignatureKeyException
     * @return bool
     */
    public function check(array $auth, array $signature, $prefix)
    {
        if (! isset($auth[$prefix . 'key'])) {
            throw new SignatureKeyException('The authentication key has not been set');
        }

        if ($auth[$prefix . 'key'] !== $signature[$prefix . 'key']) {
            throw new SignatureKeyException('The authentication key is not valid');
        }

        return true;
    }
}
