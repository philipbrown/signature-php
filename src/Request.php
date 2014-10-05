<?php namespace PhilipBrown\Signature;

use Carbon\Carbon;

class Request
{
    /**
     * @var string
     */
    private $version = '3.0';

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
     * Create a new Request
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     */
    public function __construct($method, $uri, array $params)
    {
        $this->method = strtoupper($method);
        $this->uri    = $uri;
        $this->params = $params;
    }

    /**
     * Sign the Request with a Token
     *
     * @param Token $token
     * @return array
     */
    public function sign(Token $token)
    {
        $auth = [
            'auth_version'   => $this->version,
            'auth_key'       => $token->key(),
            'auth_timestamp' => Carbon::now()->timestamp
        ];

        $payload = $this->payload($auth, $this->params);

        $signature = $this->signature($payload, $this->method, $this->uri, $token->secret());

        $auth['auth_signature'] = $signature;

        return $auth;
    }

    /**
     * Create the payload
     *
     * @param array $auth
     * @param array $params
     * @return array
     */
    public function payload(array $auth, array $params)
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
    public function signature(array $payload, $method, $uri, $secret)
    {
        $payload = urldecode(http_build_query($payload));

        $payload = implode("\n", [$method, $uri, $payload]);

        return hash_hmac('sha256', $payload, $secret);
    }
}
