<?php namespace PhilipBrown\Signature\Tests;

use Carbon\Carbon;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $auth;

    /** @var array */
    private $params;

    /** @var Token */
    private $token;

    /** @var Request */
    private $request;

    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->auth = [
            'auth_version'   => '3.0.4',
            'auth_key'       => 'abc123',
            'auth_timestamp' => Carbon::now()->timestamp
        ];
        $this->params  = ['name' => 'Philip Brown'];
        $this->token   = new Token('abc123', 'qwerty');
        $this->request = new Request('POST', 'users', $this->params);
    }

    /** @test */
    public function should_create_payload()
    {
        $payload = $this->request->payload($this->auth, $this->params);

        $this->assertEquals([
            'auth_key'       => 'abc123',
            'auth_timestamp' => '1412506800',
            'auth_version'   => '3.0.4',
            'name'           => 'Philip Brown'
        ], $payload);
    }

    /** @test */
    public function should_create_signature()
    {
        $payload = $this->request->payload($this->auth, $this->params);

        $signature = $this->request->signature($payload, 'POST', 'users', 'qwerty');

        $this->assertEquals(
            '58df9a58bc27f8722481c8b97233855cc5bb0c42e2c141e6858c0130edbfa8bd', $signature);
    }

    /** @test */
    public function should_sign_request()
    {
        $auth = $this->request->sign($this->token);

        $this->assertEquals('3.0.4', $auth['auth_version']);
        $this->assertEquals('abc123', $auth['auth_key']);
        $this->assertEquals('1412506800', $auth['auth_timestamp']);
        $this->assertEquals(
            '58df9a58bc27f8722481c8b97233855cc5bb0c42e2c141e6858c0130edbfa8bd', $auth['auth_signature']);
    }
}

