<?php namespace PhilipBrown\Signature\Tests\Guards;

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\Guards\CheckKey;

class CheckKeyTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckTokenKey */
    private $guard;

    /** @var Signature */
    private $signature;

    public function setUp()
    {
        $this->guard = new CheckKey;
        $this->signature = new Signature(new Token('key', 'secret'), 'POST', 'users', []);
    }

    /** @test */
    public function should_throw_exception_on_missing_key()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check([], $this->signature);
    }

    /** @test */
    public function should_throw_exception_on_invalid_key()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check(['key' => ''], $this->signature);
    }

    /** @test */
    public function should_return_true_with_valid_key()
    {
        $this->assertTrue($this->guard->check(['key' => 'key'], $this->signature));
    }
}
