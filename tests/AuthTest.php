<?php namespace PhilipBrown\Signature\Tests;

use Carbon\Carbon;
use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->token = new Token('key', 'secret');

        $this->payload = [
            'name' => 'Philip Brown',
            'auth' => [
                'version'   => '2.0',
                'key'       => 'key',
                'timestamp' => Carbon::now()->timestamp,
                'hash'      => '7e66810eb38bb6421925c246ebc7e0efbb0a80fb697c7393d9f6d35e677e65d6'
            ]
        ];

        $this->auth = new Auth($this->token, 'POST', 'users', $this->payload);
    }

    /** @test */
    public function should_get_body_from_payload()
    {
        $body = $this->auth->body();

        $this->assertEquals('Philip Brown', $body['name']);
    }

    /** @test */
    public function should_get_signature_from_payload()
    {
        $auth = $this->auth->signature();

        $this->assertEquals('2.0', $auth['version']);
        $this->assertEquals('key', $auth['key']);
        $this->assertEquals(1412506800, $auth['timestamp']);
        $this->assertEquals(
            '7e66810eb38bb6421925c246ebc7e0efbb0a80fb697c7393d9f6d35e677e65d6', $auth['hash']);
    }

    /** @test */
    public function should_throw_exception_on_missing_signature()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $auth = new Auth($this->token, 'POST', 'users', []);

        $auth = $auth->signature();
    }

    /** @test */
    public function should_authenticate_successfully()
    {
        $this->assertTrue($this->auth->attempt());
    }

    /** @test */
    public function should_throw_exception_on_invalid_key()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->payload['auth']['key'] = '_key';

        $auth = new Auth($this->token, 'POST', 'users', $this->payload);

        $auth->attempt();
    }

    /** @test */
    public function should_throw_exception_on_invalid_version()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->payload['auth']['version'] = '1.0';

        $auth = new Auth($this->token, 'POST', 'users', $this->payload);

        $auth->attempt();
    }

    /** @test */
    public function should_throw_exception_on_invalid_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->payload['auth']['timestamp'] = Carbon::now()->addHour()->timestamp;

        $auth = new Auth($this->token, 'POST', 'users', $this->payload);

        $auth->attempt();
    }

    /** @test */
    public function should_throw_exception_on_invalid_hash()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->payload['auth']['hash'] = '';

        $auth = new Auth($this->token, 'POST', 'users', $this->payload);

        $auth->attempt();
    }
}
