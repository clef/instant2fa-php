# instant2fa-php

A PHP client for [Instant2FA](https://instant2fa.com).

## Installation

Install it with composer: 

```
composer require clef/instant2fa
```

Requires PHP 5.4 or newer.

## Usage

See our [integration guide](http://docs.instant2fa.com/) for a full walkthrough of the integration. The integration will take you about an hour.

Here's a flavor of the methods you'll be using:

```php

$instant2fa = new \Instant2FA\Instant2FA([
    'access_key' => ACCESS_KEY,
    'access_secret' => ACCESS_SECRET
]);
                                
$distinct_id = "A_UNIQUE_ID_FOR_A_GIVEN_USER";

// To show hosted 2FA settings:
$hosted_page_url = $instant2fa->create_settings($distinct_id);

// To show a hosted verification page:
try {
    $hosted_page_url = $instant2fa->create_verification($distinct_id);
    // Redirect to 2FA verification page
} catch (\Instant2FA\Error\MFANotEnabled $e) {
    // Log the user in as normal
}

// To see whether a user successfully completed 2FA verification:
$succeeded = $instant2fa->confirm_verification($distinct_id, $request->input('instant2faToken'));
```

