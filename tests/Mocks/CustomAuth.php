<?php namespace PhilipBrown\Signature\Tests\Mocks;

use PhilipBrown\Signature\Auth;

class CustomAuth extends Auth
{
    protected $request = "PhilipBrown\Signature\Tests\Mocks\CustomRequest";
}
