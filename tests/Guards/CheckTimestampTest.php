<?php namespace PhilipBrown\Signature\Tests\Guards;

use PhilipBrown\Signature\Guards\CheckTimestamp;

class CheckTimestampTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckTokenKey */
    private $guard;

    public function setUp()
    {
        $this->guard = new CheckTimestamp;
    }

    /** @test */
    public function should_throw_exception_on_missing_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureTimestampException');

        $this->guard->check([], [], 'auth_');
    }

    /** @test */
    public function should_throw_exception_on_expired_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureTimestampException');

        $timestamp = time() + 60 * 60;

        $this->guard->check(['auth_timestamp' => $timestamp], [], 'auth_');
    }

    /** @test */
    public function should_throw_exception_on_future_expired_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureTimestampException');

        $timestamp = time() - 60 * 60;

        $this->guard->check(['auth_timestamp' => $timestamp], [], 'auth_');
    }

    /** @test */
    public function should_return_true_with_valid_timestamp()
    {
        $timestamp = time();

        $this->guard->check(['auth_timestamp' => $timestamp], [], 'auth_');
    }
}
