<?php namespace PhilipBrown\Signature\Tests;

use Carbon\Carbon;
use PhilipBrown\Signature\Auth;
use PhilipBrown\Signature\Token;

class AuthTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Carbon::setTestNow(Carbon::create(2014, 10, 5, 12, 0, 0, 'Europe/London'));

        $this->params = [
            'auth_version'   => '3.0',
            'auth_key'       => 'abc123',
            'auth_timestamp' => '1412506800',
            'auth_signature' => 'b84592eb9b80522759eedb195aed818ebd59e29fc787cca1df5c14ef82d6c897',
            'name' => 'Philip Brown'
        ];

        $this->token = new Token('abc123', 'qwerty');
    }

    /** @test */
    public function should_throw_exception_on_invalid_version()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureVersionException');

        $this->params['auth_version'] = '2.0';

        $auth = new Auth('POST', 'users', $this->params);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_key()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureKeyException');

        $this->params['auth_key'] = 'edf456';

        $auth = new Auth('POST', 'users', $this->params);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_timestamp()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureTimestampException');

        $this->params['auth_timestamp'] = Carbon::now()->addHour()->timestamp;

        $auth = new Auth('POST', 'users', $this->params);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_throw_exception_on_invalid_signature()
    {
        $this->setExpectedException('PhilipBrown\Signature\Exceptions\SignatureSignatureException');

        $this->params['auth_signature'] = '';

        $auth = new Auth('POST', 'users', $this->params);

        $auth->attempt($this->token);
    }

    /** @test */
    public function should_return_true_on_successfull_authentication()
    {
        $auth = new Auth('POST', 'users', $this->params);

        $this->assertTrue($auth->attempt($this->token));
    }
}
