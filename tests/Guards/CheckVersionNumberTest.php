<?php namespace PhilipBrown\Signature\Tests\Guards;

use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\Guards\CheckVersion;

class CheckVersionTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckVersionNumber */
    private $guard;

    /** @var Signature */
    private $signature;

    public function setUp()
    {
        $this->guard = new CheckVersion;
        $this->signature = new Signature(new Token('key', 'secret'), 'POST', 'users', []);
    }

    /** @test */
    public function should_throw_exception_on_missing_version_number()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check([], $this->signature);
    }

    /** @test */
    public function should_throw_exception_on_invalid_version_number()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check(['version' => '1.1'], $this->signature);
    }

    /** @test */
    public function should_return_true_with_valid_version_number()
    {
        $this->assertTrue($this->guard->check(['version' => '2.0'], $this->signature));
    }
}
