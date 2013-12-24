<?php namespace Philipbrown\Signplz\Test;

use Philipbrown\Signplz\Signplz;

class SignplzTest extends TestCase {

  public function makeRequest()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create params
    $params = array('name' => 'Philip Brown', 'email' => 'phil@ipbrown.com');

    // Create Token
    $token = $signplz->token('my_key', 'my_secret');

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $params);

    // Sign the request
    $auth_params = $request->sign($token);

    // Create query params
    return array_merge($params, $auth_params);
  }

 /**
  * @expectedException Exception
  */
  public function testExceptionWhenRequestParamsNotArray()
  {
    // Create new Signplz instance
    $signplz = new Signplz;
    $request = $signplz->request('POST', '/api/thing', 'not an array');
  }

  public function testAuthenticateRequestSuccess()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create Token
    $token = $signplz->token('my_key', 'my_secret');

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $this->makeRequest());

    // Assert authenticated request
    $this->assertTrue($request->authenticate($token));
  }

 /**
  * @expectedException Philipbrown\Signplz\Exception\AuthenticationException
  * @expectedExceptionMessage The auth_key is incorrect
  */
  public function testAuthenticationRequestIncorrectKey()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create Token
    $token = $signplz->token('not_my_key', 'my_secret');

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $this->makeRequest());

    // Attempt to authenticate
    $request->authenticate($token);
  }

 /**
  * @expectedException Philipbrown\Signplz\Exception\AuthenticationException
  * @expectedExceptionMessage The token secret is not set
  */
  public function testAuthenticationRequestIncorrectSecret()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create Token
    $token = $signplz->token('my_key', null);

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $this->makeRequest());

    // Attempt to authenticate
    $request->authenticate($token);
  }

 /**
  * @expectedException Philipbrown\Signplz\Exception\AuthenticationException
  * @expectedExceptionMessage The auth_version is incorrect
  */
  public function testAuthenticationRequestIncorrectVersion()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create Token
    $token = $signplz->token('my_key', 'not_my_secret');

    // Change params
    $params = $this->makeRequest();
    $params['auth_version'] = '1.1';

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $params);

    // Attempt to authenticate
    $request->authenticate($token);
  }


 /**
  * @expectedException Philipbrown\Signplz\Exception\AuthenticationException
  * @expectedExceptionMessage The auth_timestamp is invalid
  */
  public function testAuthenticationRequestIncorrectTimestamp()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create Token
    $token = $signplz->token('my_key', 'my_secret');

    // Change params
    $params = $this->makeRequest();
    $params['auth_timestamp'] = time() + (7 * 24 * 60 * 60);

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $params);

    // Attempt to authenticate
    $request->authenticate($token);
  }

 /**
  * @expectedException Philipbrown\Signplz\Exception\AuthenticationException
  * @expectedExceptionMessage The auth_signature is incorrect
  */
  public function testAuthenticationRequestIncorrectSignature()
  {
    // Create new Signplz instance
    $signplz = new Signplz;

    // Create Token
    $token = $signplz->token('my_key', 'my_secret');

    // Change params
    $params = $this->makeRequest();
    $params['auth_signature'] = 'sucure signature. many character. so wow';

    // Create Request
    $request = $signplz->request('POST', '/api/thing', $params);

    // Attempt to authenticate
    $request->authenticate($token);
  }

}
