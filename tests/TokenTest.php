<?php namespace PhilipBrown\Signature\Tests;

use PhilipBrown\Signature\Token;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function should_create_token()
    {
        $token = new Token('key', 'secret');

        $this->assertInstanceOf('PhilipBrown\Signature\Token', $token);
        $this->assertEquals('key', $token->key());
        $this->assertEquals('secret', $token->secret());
    }
}
