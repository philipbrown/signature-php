<?php namespace PhilipBrown\Signature\Tests\Guards;

use Carbon\Carbon;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Signature;
use PhilipBrown\Signature\Guards\CheckTimestamp;

class CheckTimestampTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckTokenKey */
    private $guard;

    /** @var Signature */
    private $signature;

    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->guard = new CheckTimestamp;
        $this->signature = new Signature(new Token('key', 'secret'), 'POST', 'users', []);
    }

    /** @test */
    public function should_throw_exception_on_missing_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $this->guard->check([], $this->signature);
    }

    /** @test */
    public function should_throw_exception_on_expired_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\SignatureException');

        $timestamp = Carbon::now()->addHour()->timestamp;

        $this->guard->check(['timestamp' => $timestamp], $this->signature);
    }

    /** @test */
    public function should_return_true_with_valid_timestamp()
    {
        $timestamp = Carbon::now()->timestamp;

        $this->guard->check(['timestamp' => $timestamp], $this->signature);
    }
}
