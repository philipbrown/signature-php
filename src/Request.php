<?php namespace PhilipBrown\Signature;

use Carbon\Carbon;

class Request
{

    const VERSION = '5.1.0';

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $params;

    /**
     * @var integer
     */
    private $timestamp;

    const PREFIX = 'auth_';

    /**
     * Create a new Request
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param integer $timestamp
     */
    public function __construct($method, $uri, array $params, $timestamp = null)
    {
        $this->method    = strtoupper($method);
        $this->uri       = $uri;
        $this->params    = $params;
        $this->timestamp = $timestamp ?: Carbon::now()->timestamp;
    }

    /**
     * Sign the Request with a Token
     *
     * @param Token  $token
     * @param string $prefix
     * @return array
     */
    public function sign(Token $token, $prefix = self::PREFIX)
    {
        $auth = [
            $prefix . 'version'   => self::VERSION,
            $prefix . 'key'       => $token->key(),
            $prefix . 'timestamp' => $this->timestamp,
        ];

        $payload = $this->payload($auth, $this->params);

        $signature = $this->signature($payload, $this->method, $this->uri, $token->secret());

        $auth[$prefix . 'signature'] = $signature;

        return $auth;
    }

    /**
     * Create the payload
     *
     * @param array $auth
     * @param array $params
     * @return array
     */
    private function payload(array $auth, array $params)
    {
        $payload = array_merge($auth, $params);
        array_change_key_case($payload, CASE_LOWER);

        ksort($payload);

        return $payload;
    }

    /**
     * Create the signature
     *
     * @param array $payload
     * @param string $method
     * @param string $uri
     * @param string $secret
     * @return string
     */
    private function signature(array $payload, $method, $uri, $secret)
    {
        $payload = urldecode(http_build_query($payload));

        $payload = implode("\n", [$method, $uri, $payload]);

        return hash_hmac('sha256', $payload, $secret);
    }
}
