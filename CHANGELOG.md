# CHANGELOG

## 5.1.2

* Set the value of `array_change_key_case`
* Set default value for request params to empty array

## 5.1.0

* Add the ability to use your own prefix for instead of `auth_*`. The default is still `auth_*` so no BC breaks are introduced.

## 5.0.0

* `Request::payload()` and `Request::signature()` methods are now private. The `Request::sign()` method should be used instead.

## 4.0.0

* Fixes a security flaw due to not including `auth_*` fields in the signature payload.

## 3.0.3

* Fixed a bug where the `CheckTimestamp` guard would only protect against request timestamps in the past.

## 3.0.2

## 3.0.1

## 3.0.0

## 2.0.0

## 1.0.1

## 1.0.0
