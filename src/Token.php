<?php namespace PhilipBrown\Signature;

class Token
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * Create a new Token
     *
     * @param string $key
     * @param string $secret
     * @return void
     */
    public function __construct($key, $secret)
    {
        $this->key    = $key;
        $this->secret = $secret;
    }

    /**
     * Get the key
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the secret
     *
     * @return string
     */
    public function secret()
    {
        return $this->secret;
    }
}
