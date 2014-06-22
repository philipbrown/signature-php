<?php namespace PhilipBrown\Signplz;

use PhilipBrown\Signplz\Exception\AuthenticationException;

class Request {

  /**
   * The HTTP method
   *
   * @var string
   */
  protected $method;

  /**
   * The API path
   *
   * @var string
   */
  protected $path;

  /**
   * The data to send
   *
   * @var array
   */
  protected $params;

  /**
   * The version of Signplz
   *
   * @var string
   */
  protected $version = '1.0';

  /**
   * The default auth parameters
   *
   * @var array
   */
  protected $auth_params = array(
    'auth_version'    => null,
    'auth_key'        => null,
    'auth_timestamp'  => null,
    'auth_signature'  => null
  );

  /**
   * The query params
   *
   * @var array
   */
  protected $query_params = array();

  /**
   * Create a new instance of Request
   *
   * @param string $method
   * @param string $path
   * @param array $params
   * @return void
   */
  public function __construct($method, $path, array $params)
  {
    // Set method
    $this->method = strtoupper($method);

    // Set path
    $this->path = $path;

    // Set the params
    foreach($params as $k => $v)
    {
      $k = strtolower($k);
      substr($k, 0, 5) == 'auth_' ? $this->auth_params[$k] = $v : $this->query_params[$k] = $v;
    }
  }

  /**
   * Sign the request with a token
   *
   * @param PhilipBrown\SignPlz\Token $token
   * @return array
   */
  public function sign(Token $token)
  {
    $this->auth_params = array(
      'auth_version'    => '1.0',
      'auth_key'        => $token->getKey(),
      'auth_timestamp'  => time(),
    );

    $this->auth_params['auth_signature'] = $this->signature($token);

    return $this->auth_params;
  }

  /**
   * Get the hashed signature
   *
   * @param PhilipBrown\Signplz\Token $token
   * @return string
   */
  protected function signature(Token $token)
  {
    return hash_hmac('sha256', $this->stringToSign(), $token->getSecret());
  }

  /**
   * String To Sign
   *
   * @return string
   */
  protected function stringToSign()
  {
    return implode("\n", array($this->method, $this->path, $this->parameterString()));
  }

  /**
   * Parameter String
   *
   * @return string
   */
  protected function parameterString()
  {
    // Create an array to build the http query
    $array = array();

    // Merge the auth and query params
    $params = array_merge($this->auth_params, $this->query_params);

    // Convert keys to lowercase
    foreach($params as $k => $v)
    {
      // Set each param on the array
      $array[strtolower($k)] = $v;
    }

    // Remove the signature key
    unset($array['auth_signature']);

    // Encode array to http string
    return http_build_query($array);
  }

  /**
   * Authenticate the request
   *
   * @param PhilipBrown\Signplz\Token $token
   * @param int $timestampGrace
   */
  public function authenticate(Token $token, $timestampGrace = 600)
  {
    // Check the authentication key is correct
    if($this->auth_params['auth_key'] == $token->getKey())
    {
      return $this->authenticateByToken($token, $timestampGrace);
    }

    throw new AuthenticationException('The auth_key is incorrect');
  }

  /**
   * Authenticate By Token
   *
   * @param PhilipBrown\SignPlz\Token $token
   * @param int $timestampGrace
   * @return bool
   */
  protected function authenticateByToken(Token $token, $timestampGrace)
  {
    // Check token
    if($token->getSecret() == null)
    {
      throw new AuthenticationException('The token secret is not set');
    }

    // Validate version
    $this->validateVersion();

    // Validate timestamp
    $this->validateTimestamp($timestampGrace);

    // Validate signature
    $this->validateSignature($token);

    return true;
  }

  /**
   * Validate Version
   *
   * @return bool
   */
  protected function validateVersion()
  {
    if($this->auth_params['auth_version'] !== $this->version)
    {
      throw new AuthenticationException('The auth_version is incorrect');
    }

    return true;
  }

  /**
   * Validate Timestamp
   *
   * @param int $timestampGrace
   * @return bool
   */
  protected function validateTimestamp($timestampGrace)
  {
    if($timestampGrace == 0)
    {
      return true;
    }

    $difference = $this->auth_params['auth_timestamp'] - time();

    if($difference >= $timestampGrace)
    {
      throw new AuthenticationException('The auth_timestamp is invalid');
    }

    return true;
  }

  /**
   * Validate Signature
   *
   * @param PhilipBrown\Signplz\Token $token
   * @return bool
   */
  protected function validateSignature(Token $token)
  {
    if($this->auth_params["auth_signature"] !== $this->signature($token))
    {
      throw new AuthenticationException('The auth_signature is incorrect');
    }

    return true;
  }

}
