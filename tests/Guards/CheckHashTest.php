<?php namespace PhilipBrown\Signature\Tests\Guards;

use Carbon\Carbon;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\Guards\CheckHash;

class CheckHashTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckTokenKey */
    private $guard;

    /** @var Signature */
    private $signature;

    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->guard = new CheckHash;
        $this->signature = new Signature(new Token('key', 'secret'), 'POST', 'users', []);
    }

    /** @test */
    public function should_throw_exception_on_missing_hash()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check([], $this->signature);
    }

    /** @test */
    public function should_throw_exception_on_invalid_hash()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check(['hash' => 'hello'], $this->signature);
    }

    /** @test */
    public function should_return_true_with_valid_hash()
    {
        $hash = '74386c24552f7a044bba201b46bd713d40050b9c411d8e7d0aeb98e7e3ed6e83';

        $this->assertTrue($this->guard->check(['hash' => $hash], $this->signature));
    }
}
