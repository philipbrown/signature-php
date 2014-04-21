<?php namespace PhilipBrown\Signplz;

class Token {

  /**
   * @var string
   */
  protected $key;

  /**
   * @var secret
   */
  protected $secret;

  /**
   * Construct
   *
   * @param string $key
   * @param string $secret
   */
  public function __construct($key, $secret)
  {
    $this->key = $key;
    $this->secret = $secret;
  }

  /**
   * Get Key
   *
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }

  /**
   * Get Secret
   *
   * @return string
   */
  public function getSecret()
  {
    return $this->secret;
  }

}
