<?php namespace PhilipBrown\Signature\Tests;

use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;
use PhilipBrown\Signature\Guards\CheckKey;
use PhilipBrown\Signature\Guards\CheckVersion;
use PhilipBrown\Signature\Guards\CheckSignature;
use PhilipBrown\Signature\Guards\CheckTimestamp;
use PhilipBrown\Signature\Tests\Mocks\CustomRequest;
use PhilipBrown\Signature\Tests\Mocks\CustomAuth;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $params = ['name' => 'Philip Brown'];
        $this->token = new Token('abc123', 'qwerty');

        $request = new Request('POST', 'users', $params);
        $signed = $request->sign($this->token);

        $this->params = array_merge($params, $signed);
    }

    /** @test */
    public function should_throw_exception_on_invalid_version()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureVersionException');

        $this->params['auth_version'] = '2.0';

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckVersion
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_key()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureKeyException');

        $this->params['auth_key'] = 'edf456';

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckKey
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureTimestampException');

        $this->params['auth_timestamp'] = time() + 60 * 60;

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckTimestamp
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_signature()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureSignatureException');

        $this->params['auth_signature'] = '';

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckSignature
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_return_true_on_successful_authentication()
    {
        $auth = new Auth('POST', 'users', $this->params, [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp,
            new CheckSignature
        ]);

        $this->assertTrue($auth->attempt($this->token));
    }

    /** @test */
    public function should_return_true_on_successful_attempt_with_custom_prefix()
    {
        $params  = ['name' => 'Philip Brown'];
        $token   = new Token('abc123', 'qwerty');
        $request = new Request('POST', 'users', $params);
        $signed  = $request->sign($token, 'x-');
        $params  = array_merge($params, $signed);

        $token = new Token('abc123', 'qwerty');

        $auth = new Auth('POST', 'users', $params, [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp,
            new CheckSignature
        ]);

        $this->assertTrue($auth->attempt($token, 'x-'));
    }

    /** @test */
    public function should_return_true_on_successful_authentication_using_custom_auth()
    {
        $params  = ['name' => 'Philip Brown'];
        $token   = new Token('abc123', 'qwerty');
        $request = new CustomRequest('POST', 'users', $params);
        $signed  = $request->sign($token);
        $params  = array_merge($params, $signed);

        $auth = new CustomAuth('POST', 'users', $params, [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp,
            new CheckSignature
        ]);

        $this->assertTrue($auth->attempt($token));
    }
}
