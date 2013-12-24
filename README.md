# Signplz
**Key / Secret authentication for your API**

[![Build Status](https://travis-ci.org/philipbrown/signplz.png?branch=master)](https://travis-ci.org/philipbrown/signplz)

Signplz is a HMAC-SHA authentication implementation for PHP. It is basically just a port of [Signature](https://github.com/mloughran/signature) by [@mloughran](https://github.com/mloughran).

## Why Signplz?
There are a few different options for authenticating an API. HTTP Basic Auth is a common choice but has [security issues](http://swaggadocio.com/post/48223179207/why-the-hell-does-your-api-still-use-http-basic-auth). Using OAuth can massively over-complicate things if all you really need is the ```client_credential``` grant.

Signplz allows you to implement very simple key / secret authentication for your API using hashed signatures.

##Installation
Add `philipbrown/signplz` as a requirement to `composer.json`:

```json
{
  "require": {
    "philipbrown/signplz": "dev-master"
  }
}
```
Update your packages with `composer update`.

## Making a request
```php
// Create new Signplz instance
$signplz = new Signplz;

// Create data to send
$params = array('name' => 'Philip Brown', 'email' => 'phil@ipbrown.com');

// Create Token
$token = $signplz->token('my_key', 'my_secret');

// Create Request
$request = $signplz->request('POST', '/api/thing', $params);

// Sign the request
$auth_params = $request->sign($token);

// Create query params
$query_params = array_merge($params, $auth_params);

var_dump($query_params);

/*
array(6) {
  'name' => string(12) "Philip Brown"
  'email' => string(16) "phil@ipbrown.com"
  'auth_version' => string(3) "1.0"
  'auth_key' => string(6) "my_key"
  'auth_timestamp' => int(1387899087)
  'auth_signature' => string(64) "f4d3e997fa469e393f63243c4659b698dd38aef849cf01a7fdaf53ce8821c13c"
}
*/
```

Now you can create a ```POST``` request to your API by including the ```$query_params``` array. If you are unsure how to do this, take a look at [Guzzle](https://github.com/guzzle/guzzle).

## Authenticating a response
```php
// Create new Signplz instance
$signplz = new Signplz;

// Create Token
$token = $signplz->token('my_key', 'my_secret');

// Create Request
$request = $signplz->request('POST', '/api/thing', Input::all());

// Authenticated request
try
{
  $request->authenticate($token); // return true
}
catch(AuthenticationException $e)
{
  return json_encode($e->getMessage());
}
```

## Credits
This library was massively inspired by:

[Why the hell does your API still us HTTP Basic Auth?](http://swaggadocio.com/post/48223179207/why-the-hell-does-your-api-still-use-http-basic-auth) by [@stevegraham](https://github.com/stevegraham).

[Signature](https://github.com/mloughran/signature) ruby gem by [@mloughran](https://github.com/mloughran).

## License
The MIT License (MIT)

Copyright (c) 2013 Philip Brown.

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
