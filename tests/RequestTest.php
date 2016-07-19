<?php namespace PhilipBrown\Signature\Tests;

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;
use PhilipBrown\Signature\Tests\Mocks\CustomRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var Token */
    private $token;

    /** @var Request */
    private $request;

    public function setUp()
    {
        $params  = ['name' => 'Philip Brown'];
        $this->token   = new Token('abc123', 'qwerty');
        $this->request = new Request('POST', 'users', $params, 1412506800);
        $this->custom_request = new CustomRequest('POST', 'users', $params, 1412506800);
    }

    /** @test */
    public function should_sign_request()
    {
        $auth = $this->request->sign($this->token);

        $this->assertEquals('5.1.2', $auth['auth_version']);
        $this->assertEquals('abc123', $auth['auth_key']);
        $this->assertEquals('1412506800', $auth['auth_timestamp']);
        $this->assertRegExp('/[a-z0-9]{64}/', $auth['auth_signature']);
    }

    /** @test */
    public function should_accept_custom_prefix()
    {
        $auth = $this->request->sign($this->token, 'x-');

        $this->assertEquals('5.1.2', $auth['x-version']);
        $this->assertEquals('abc123', $auth['x-key']);
        $this->assertEquals('1412506800', $auth['x-timestamp']);
        $this->assertRegExp('/[a-z0-9]{64}/', $auth['x-signature']);
    }

    /** @test */
    public function should_sign_custom_request()
    {
        $auth = $this->custom_request->sign($this->token);

        $this->assertEquals('0.0.1', $auth['auth_version']);
        $this->assertEquals('abc123', $auth['auth_key']);
        $this->assertEquals('1412506800', $auth['auth_timestamp']);
        $this->assertRegExp('/[a-z0-9]{64}/', $auth['auth_signature']);
    }
}
