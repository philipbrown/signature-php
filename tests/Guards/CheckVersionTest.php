<?php namespace PhilipBrown\Signature\Tests\Guards;

use PhilipBrown\Signature\Guards\CheckVersion;

class CheckVersionTest extends \PHPUnit_Framework_TestCase
{
    /** @var CheckVersionNumber */
    private $guard;

    public function setUp()
    {
        $this->guard = new CheckVersion;
    }

    /** @test */
    public function should_throw_exception_on_missing_version_number()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureVersionException');

        $this->guard->check([], ['auth_version' => '3.0.2']);
    }

    /** @test */
    public function should_throw_exception_on_invalid_version_number()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureVersionException');

        $this->guard->check(['auth_version' => '1.1'], ['auth_version' => '3.0.2']);
    }

    /** @test */
    public function should_return_true_with_valid_version_number()
    {
        $this->assertTrue($this->guard->check(['auth_version' => '3.0.2'], ['auth_version' => '3.0.2']));
    }
}
