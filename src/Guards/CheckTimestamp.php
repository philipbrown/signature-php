<?php namespace PhilipBrown\Signature\Guards;

use Carbon\Carbon;
use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\SignatureException;

class CheckTimestamp implements Guard
{
    /**
     * @var int
     */
    private $grace;

    /**
     * Create a new CheckTimestamp Guard
     *
     * @param int $grace
     * @return void
     */
    public function __construct($grace = 600)
    {
        $this->grace = $grace;
    }

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
        if (! isset($auth['timestamp'])) {
            throw new SignatureException('The timestamp has not been set');
        }

        if (($auth['timestamp'] - Carbon::now()->timestamp) >= $this->grace) {
            throw new SignatureException('The timestamp is invalid');
        }

        return true;
    }
}
