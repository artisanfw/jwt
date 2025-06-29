# JWT
A simple JWT implementation as Service.

Since it's uncommon to change the encryption algorithm within the same project, this implementation delegates that decision to a constructor parameter. This allows the service to simplify its methods while still supporting algorithm customization when needed.  
If you require a dynamic algorithm system, this implementation may not be suitable for your use case.

## Install
```shell
  composer require artisanfw/jwt
  ```
## Instantiate the Service
```php
$algorithm = 'HS256';
$jwt = JWT::load($algorithm);
```
You can access to the supported algorithms using constants.
```php
$algorithm = \Artisan\Services\JWT::ALG_HS256
```
Al supported algorithms have a prefix `ALG_`.

## Encode
Your `$data` can be of any type.

```php
$token = JWT::i()->encode($secretKey, $expirationSeconds, $data);
```

## Decode
```php
$data = JWT::i()->decode($token, $secretKey);
```
The decoding process will return `null` if the token has expired or is invalid.

