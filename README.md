# NitroPay Sponsor Library for PHP >= 7.1

## Description

Creates a signed token for passing user identity to the sponsor client library.

## Usage

We recommend using this as a composer package.

`composer require ggsoftwarellc/nitropay-sponsor-php`

You can then sign tokens for your users like so:

```php
require __DIR__ . '/vendor/autoload.php';

$signer = new NitroPaySponsor\Signer('YOUR_PRIVATE_KEY');

$token = $signer->sign([
    'siteId' => '109', // required
    'userId' => '39281', // required
    'email '=> 'example@example.com', // optional
    'avatar' => 'https://s.gravatar.com/avatar/0d3964876826ac9554d88d5a51ea87a2?s=80', // optional
    'name' => 'John Doe', // optional
]);
```

Follow the instructions in the [sponsor documentation](https://docs.nitropay.com/sponsor) for what to do with the token from there.

You can use `$signer->getUserSubscription($userID)` to look up subscription info for a user.
