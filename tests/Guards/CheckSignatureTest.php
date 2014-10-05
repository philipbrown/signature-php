<?php namespace PhilipBrown\Signature\Tests\Guards;

use Carbon\Carbon;
use PhilipBrown\Signature\Guards\CheckSignature;

class CheckSignatureTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckTokenKey */
    private $guard;

    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->guard = new CheckSignature;
    }

    /** @test */
    public function should_throw_exception_on_missing_signature()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureSignatureException');

        $this->guard->check([], []);
    }

    /** @test */
    public function should_throw_exception_on_invalid_hash()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureException');

        $this->guard->check(['auth_signature' => 'hello'], ['auth_signature' => 'world']);
    }

    /** @test */
    public function should_return_true_with_valid_hash()
    {
        $hash = '74386c24552f7a044bba201b46bd713d40050b9c411d8e7d0aeb98e7e3ed6e83';

        $this->assertTrue($this->guard->check(['auth_signature' => $hash], ['auth_signature' => $hash]));
    }
}
