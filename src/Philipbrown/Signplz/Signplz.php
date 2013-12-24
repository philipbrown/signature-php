<?php namespace Philipbrown\Signplz;

class Signplz {

  /**
   * Token
   *
   * @param string $key
   * @param string $secret
   * @return Philipbrown\Signplz\Token
   */
  public function token($key, $secret)
  {
    return new Token($key, $secret);
  }

  /**
   * Request
   *
   * @param string $method
   * @param string $path
   * @param array $params
   */
  public function request($method, $path, array $params)
  {
    return new Request($method, $path, $params);
  }

}
