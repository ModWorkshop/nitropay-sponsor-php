# NitroPay Sponsor Library for PHP >= 7.1

## Description

Creates a signed token for passing user identity to the sponsor client library.

## Usage

We recommend using this as a composer package.

`composer require ggsoftwarellc/nitropay-sponsor-php`

You can then sign tokens for your users like so:

```php
require __DIR__ . '/vendor/autoload.php';

$sponsor = new NitroPay\Sponsor('YOUR_PRIVATE_KEY');

$sponsor->setEmail('example@example.com'); // optional: pass along the user's email to pre-fill in the form
$sponsor->setAvatar('https://s.gravatar.com/avatar/0d3964876826ac9554d88d5a51ea87a2?s=80'); // optional: avatar
$sponsor->setDisplayName('John Doe'); // optional: display name, user will be addressed by their ID if this isn't set

$token = $sponsor->sign(109, 39281); // in this example, site ID = 109, user ID = 39281
```

Follow the instructions in the [sponsor documentation](https://docs.nitropay.com/sponsor) for what to do with the token from there.
