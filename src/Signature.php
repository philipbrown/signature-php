<?php namespace PhilipBrown\Signature;

use Carbon\Carbon;

class Signature
{
    /**
     * @var string
     */
    private $version = '2.0';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var integer
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
    private $body;

    /**
     * Create a new Signature
     *
     * @param Token  $token
     * @param string $method
     * @param string $uri
     * @param array  $body
     * @return void
     */
    public function __construct(Token $token, $method, $uri, array $body)
    {
        $this->uri       = $uri;
        $this->method    = $method;
        $this->body      = $body;
        $this->key       = $token->key();
        $this->secret    = $token->secret();
        $this->timestamp = Carbon::now()->timestamp;
    }

    /**
     * Return the auth attributes
     *
     * @return array
     */
    public function auth()
    {
        return [
            'version'   => $this->version,
            'key'       => $this->key,
            'secret'    => $this->secret,
            'timestamp' => $this->timestamp
        ];
    }

    /**
     * Sign the signature
     *
     * @return array
     */
    public function sign()
    {
        $payload = $this->payload($this->auth(), $this->body);

        $hash = $this->hash($payload, $this->method, $this->uri);

        return ['auth' => array_merge($this->auth(), ['hash' => $hash])];
    }

    /**
     * Create the payload
     *
     * @param array $auth
     * @param array $body
     * @return array
     */
    public function payload(array $auth, array $body)
    {
        $payload = array_merge($auth, $body);

        array_change_key_case($payload, CASE_LOWER);

        ksort($payload);

        return $payload;
    }

    /**
     * Hash the payload
     *
     * @param array $payload
     * @param string $method
     * @param string $uri
     * @return string
     */
    public function hash(array $payload, $method, $uri)
    {
        $payload = urldecode(http_build_query($payload));

        $payload = implode("\n", [$method, $uri, $payload]);

        return hash_hmac('sha256', $payload, $this->secret);
    }
}
