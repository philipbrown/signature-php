<?php namespace PhilipBrown\Signature;

use Carbon\Carbon;
use PhilipBrown\Signature\Guards\CheckKey;
use PhilipBrown\Signature\Guards\CheckHash;
use PhilipBrown\Signature\Guards\CheckVersion;
use PhilipBrown\Signature\Guards\CheckTimestamp;

class Auth
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
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $payload;

    /**
     * Create a new Auth instance
     *
     * @param Token $token
     * @param string $method
     * @param string $uri
     * @param array $payload
     * @return void
     */
    public function __construct(Token $token, $method, $uri, $payload)
    {
        $this->uri       = $uri;
        $this->method    = $method;
        $this->payload   = $payload;
        $this->key       = $token->key();
        $this->secret    = $token->secret();
        $this->timestamp = Carbon::now()->timestamp;

        $this->guards = [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp,
            new CheckHash
        ];
    }

    /**
     * Attempt to authenticate the request
     *
     * @return bool
     */
    public function attempt()
    {
        $auth = $this->signature();
        $body = $this->body();

        $signature = new Signature(new Token($this->key, $this->secret), $this->method, $this->uri, $body);

        foreach ($this->guards as $guard) {
            $guard->check($auth, $signature);
        }

        return true;
    }

    /**
     * Get the auth parameters
     *
     * @return array
     */
    public function signature()
    {
        if (isset($this->payload['auth'])) {
            return $this->payload['auth'];
        }

        throw new SignatureException('The request does not contain authentication details');
    }

    /**
     * Get the body
     *
     * @return array
     */
    public function body()
    {
        return array_diff_key($this->payload, array_flip(['auth']));
    }
}
