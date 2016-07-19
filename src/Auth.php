<?php namespace PhilipBrown\Signature;

class Auth
{
    protected $request = "PhilipBrown\Signature\Request";

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
     * @var array
     */
    private $auth = [
        'key',
        'version',
        'timestamp',
        'signature'
    ];

    /**
     * Create a new Auth instance
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param array $guards
     * @return void
     */
    public function __construct($method, $uri, array $params, array $guards)
    {
        $this->method = strtoupper($method);
        $this->uri    = $uri;
        $this->params = $params;
        $this->guards = $guards;
    }

    /**
     * Attempt to authenticate a request
     *
     * @param Token  $token
     * @param string $prefix
     * @return bool
     */
    public function attempt(Token $token, $prefix = Request::PREFIX)
    {
        $auth = $this->getAuthParams($prefix);
        $body = $this->getBodyParams($prefix);

        $request_class = $this->request;
        $request   = new $request_class($this->method, $this->uri, $body, $auth[$prefix . 'timestamp']);
        $signature = $request->sign($token, $prefix);

        foreach ($this->guards as $guard) {
            $guard->check($auth, $signature, $prefix);
        }

        return true;
    }

    /**
     * Get the auth params
     *
     * @param $prefix
     * @return array
     */
    private function getAuthParams($prefix)
    {
        return array_intersect_key($this->params, array_flip($this->addPrefix($this->auth, $prefix)));
    }

    /**
     * Get the body params
     *
     * @param $prefix
     * @return array
     */
    private function getBodyParams($prefix)
    {
        return array_diff_key($this->params, array_flip($this->addPrefix($this->auth, $prefix)));
    }

    /**
     * @param array $auth
     * @param       $prefix
     *
     * @return array
     */
    private function addPrefix(array $auth, $prefix)
    {
        return array_map(function ($item) use ($prefix) {
            return $prefix . $item;
        }, $auth);
    }
}
