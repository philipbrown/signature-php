<?php namespace PhilipBrown\Signplz;

class Token {

  /**
   * The key
   *
   * @var string
   */
  protected $key;

  /**
   * The secret
   *
   * @var secret
   */
  protected $secret;

  /**
   * Create a new instance of Token
   *
   * @param string $key
   * @param string $secret
   * @return void
   */
  public function __construct($key, $secret)
  {
    $this->key = $key;
    $this->secret = $secret;
  }

  /**
   * Get the key
   *
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }

  /**
   * Get the secret
   *
   * @return string
   */
  public function getSecret()
  {
    return $this->secret;
  }

}
