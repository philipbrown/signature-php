<?php namespace PhilipBrown\Signature\Guards;

use PhilipBrown\Signature\Exceptions\SignatureSignatureException;

class CheckSignature implements Guard
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
        if (! isset($auth['auth_signature'])) {
            throw new SignatureSignatureException('The signature has not been set');
        }

        if ($auth['auth_signature'] !== $signature['auth_signature']) {
            throw new SignatureSignatureException('The signature is not valid');
        }

        return true;
    }
}
