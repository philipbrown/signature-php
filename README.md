# Signature

**A PHP 5.4+ port of the [Signature](https://github.com/mloughran/signature) ruby gem**

[![Build Status](https://travis-ci.org/philipbrown/signature-php.png?branch=master)](https://travis-ci.org/philipbrown/signature-php)
[![Code Coverage](https://scrutinizer-ci.com/g/philipbrown/signature-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/philipbrown/signature-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/philipbrown/signature-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/philipbrown/signature-php/?branch=master)

## Installation
Add `philipbrown/signature-php` as a requirement to `composer.json`:

```json
{
  "require": {
    "philipbrown/signature-php": "~2.0"
  }
}
```
Update your packages with `composer update`.

## Making a request
```php
// The data you want to send to the API:
$data = ['name' => 'Philip Brown'];

// Create a new Token using your `key` and `secret`:
$token = new Token('key', 'secret');

// Create a new signature and pass the HTTP method, the endpoint and the data you want to send:
$signature = new Signature($token, 'POST', 'users', $data);

// Sign the signature
$auth = $signature->sign();

// Merge the `$auth` and the `$data`:
$data = array_merge($data, $auth);
```

## Authenticating a response
```
// Create a new Token using the client's `key` and `secret`:
$token = new Token('key', 'secret');

// Create a new Auth and pass in the Token, HTTP method, endpoint and request data:
$auth = new Auth($token, 'POST', 'users', $_POST);

// Attempt to authenticate the request:
try {
    $auth->attempt();
}

catch (SignatureException $e) {
    // return 4XX HTTP response
}
```
