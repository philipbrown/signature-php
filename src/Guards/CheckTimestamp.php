<?php namespace PhilipBrown\Signature\Guards;

use Carbon\Carbon;
use PhilipBrown\Signature\Exceptions\SignatureTimestampException;

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
     * @param array $signature
     * @return bool
     */
    public function check(array $auth, array $signature)
    {
        if (! isset($auth['auth_timestamp'])) {
            throw new SignatureTimestampException('The timestamp has not been set');
        }

        if (($auth['auth_timestamp'] - Carbon::now()->timestamp) >= $this->grace) {
            throw new SignatureTimestampException('The timestamp is invalid');
        }

        return true;
    }
}
