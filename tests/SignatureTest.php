<?php namespace PhilipBrown\Signature;

use Carbon\Carbon;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Signature;

class SignatureTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $token           = new Token('key', 'secret');
        $this->auth      = ['name' => 'Philip Brown'];
        $this->body      = ['version' => '2.0'];
        $this->signature = new Signature($token, 'POST', 'users', $this->body);
    }

    /** @test */
    public function should_return_the_auth_parameters()
    {
        $auth = $this->signature->auth();

        $this->assertEquals('2.0', $auth['version']);
        $this->assertEquals('key', $auth['key']);
        $this->assertEquals('secret', $auth['secret']);
        $this->assertEquals(1412506800, $auth['timestamp']);
    }

    /** @test */
    public function should_create_payload()
    {
        $payload = $this->signature->payload($this->auth, $this->body);

        $this->assertEquals('Philip Brown', $payload['name']);
        $this->assertEquals('2.0', $payload['version']);
    }

    /** @test */
    public function should_create_hash()
    {
        $payload = $this->signature->payload($this->auth, $this->body);

        $signature = $this->signature->hash($payload, 'POST', 'users');

        $this->assertEquals('83a84d0681d61be9498f358ddbd1ef60a28f5ffe2a27e2f999200de7ab6532f9', $signature);
    }

    /** @test */
    public function should_sign_the_signature()
    {
        $signature = $this->signature->sign();

        $this->assertEquals('2.0', $signature['auth']['version']);
        $this->assertEquals('key', $signature['auth']['key']);
        $this->assertEquals('secret', $signature['auth']['secret']);
        $this->assertEquals(1412506800, $signature['auth']['timestamp']);
        $this->assertEquals(
            '74386c24552f7a044bba201b46bd713d40050b9c411d8e7d0aeb98e7e3ed6e83',
            $signature['auth']['hash']
        );
    }
}
