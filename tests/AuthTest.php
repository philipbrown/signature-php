<?php namespace PhilipBrown\Signature\Tests;

use Carbon\Carbon;
use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;
use PhilipBrown\Signature\Guards\CheckKey;
use PhilipBrown\Signature\Guards\CheckVersion;
use PhilipBrown\Signature\Guards\CheckSignature;
use PhilipBrown\Signature\Guards\CheckTimestamp;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->params = [
            'auth_version'   => '5.1.0',
            'auth_key'       => 'abc123',
            'auth_timestamp' => '1412506800',
            'auth_signature' => '1144e9c47773e38d4436cf48bf32a9968a3f41c829e1a70129d690461b4abb0f',
            'name'           => 'Philip Brown'
        ];

        $this->token = new Token('abc123', 'qwerty');
    }

    /** @test */
    public function should_throw_exception_on_invalid_version()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureVersionException');

        $this->params['auth_version'] = '2.0';

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckVersion
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_key()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureKeyException');

        $this->params['auth_key'] = 'edf456';

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckKey
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureTimestampException');

        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->params['auth_timestamp'] = Carbon::now()->addHour()->timestamp;

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckTimestamp
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_signature()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureSignatureException');

        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->params['auth_signature'] = '';

        $auth = new Auth('POST', 'users', $this->params, [
            new CheckSignature
        ]);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_return_true_on_successful_authentication()
    {
        $auth = new Auth('POST', 'users', $this->params, [
            new CheckKey,
            new CheckVersion,
            new CheckTimestamp,
            new CheckSignature
        ]);

        $this->assertTrue($auth->attempt($this->token));
    }
}
