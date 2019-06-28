# NitroPay Sponsor Library for PHP >= 7.1

## Description

Creates a signed token for passing user identity to the sponsor client library.

```php
$sponsor = new Sponsor('YOUR PRIVATE_KEY');
$token = $sponsor->sign(109, 39281); // in this example, site ID = 109, user ID = 39281
```
