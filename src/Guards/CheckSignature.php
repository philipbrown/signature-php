<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Exceptions\SignatureSignatureException;

class CheckSignature implements Guard
{

    /**
     * Check to ensure the auth parameters
     * satisfy the rule of the guard
     *
     * @param array  $auth
     * @param array  $signature
     * @param string $prefix
     * @throws SignatureSignatureException
     * @return bool
     */
    public function check(array $auth, array $signature, $prefix)
    {
        if (! isset($auth[$prefix . 'signature'])) {
            throw new SignatureSignatureException('The signature has not been set');
        }

        if ($auth[$prefix . 'signature'] !== $signature[$prefix . 'signature']) {
            throw new SignatureSignatureException('The signature is not valid');
        }

        return true;
    }
}
