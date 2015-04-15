<?php namespace PhilipBrown\Signature\Tests;

use Carbon\Carbon;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var Token */
    private $token;

    /** @var Request */
    private $request;

    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $params  = ['name' => 'Philip Brown'];
        $this->token   = new Token('abc123', 'qwerty');
        $this->request = new Request('POST', 'users', $params);
    }

    /** @test */
    public function should_sign_request()
    {
        $auth = $this->request->sign($this->token);

        $this->assertEquals('5.1.0', $auth['auth_version']);
        $this->assertEquals('abc123', $auth['auth_key']);
        $this->assertEquals('1412506800', $auth['auth_timestamp']);
        $this->assertEquals(
            '1144e9c47773e38d4436cf48bf32a9968a3f41c829e1a70129d690461b4abb0f', $auth['auth_signature']);
    }

    /** @test */
    public function should_accept_custom_prefix()
    {
        $auth = $this->request->sign($this->token, 'x-');

        $this->assertEquals('5.1.0', $auth['x-version']);
        $this->assertEquals('abc123', $auth['x-key']);
        $this->assertEquals('1412506800', $auth['x-timestamp']);
        $this->assertEquals(
            'efb40418fdab26f11fead90f3d0e469ae5a21f3dd915613f6a76798124811f7b', $auth['x-signature']);
    }
}
