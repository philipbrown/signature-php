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

        $this->assertEquals('5.0.0', $auth['auth_version']);
        $this->assertEquals('abc123', $auth['auth_key']);
        $this->assertEquals('1412506800', $auth['auth_timestamp']);
        $this->assertEquals(
            'bafd7d0804142e81c5114f8a3fc23f82e324c5ad427e955d08d684ab6dbf20c6', $auth['auth_signature']);
    }

    /** @test */
    public function should_accept_custom_prefix()
    {
        $auth = $this->request->sign($this->token, 'x-');

        $this->assertEquals('5.0.0', $auth['x-version']);
        $this->assertEquals('abc123', $auth['x-key']);
        $this->assertEquals('1412506800', $auth['x-timestamp']);
        $this->assertEquals(
            '4fed31bd83f9ddec343a19d4bde4d0db168715a8c3e663ebda253a12e4e75e6f', $auth['x-signature']);
    }
}
