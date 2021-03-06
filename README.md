# PHP client for Mailtrap

## Installation

First install this package using composer:

```composer require stephanecoinon/mailtrap```

### Plain Vanilla PHP

```php
use StephaneCoinon\Mailtrap\Client;
use StephaneCoinon\Mailtrap\Model;

// Instantiate Mailtrap API client
$client = new Client('your_mailtrap_api_token_here');

// Boot API models
Model::boot($client);
```

### Laravel

This package includes a Laravel service provider (tested for Laravel v5.4).

Add your Mailtrap API token to your ```.env``` file.

```ini
MAILTRAP_API_TOKEN=your_mailtrap_api_token_here
```

Then add mailtrap in your ```config/services.php``` configuration:

```php
'mailtrap' => [
    'token' => env('MAILTRAP_API_TOKEN'),
],
```

Finally register the Mailtrap service provider in your ```config/app.php```:

```php
'providers' => [
    // ...

    /*
     * Package Service Providers...
     */
    // ...

    StephaneCoinon\Mailtrap\MailtrapServiceProvider::class,

    // ...
],
```

## Usage

```php
use StephaneCoinon\Mailtrap\Inbox;

// Fetch all inboxes
$inboxes = Inbox::all();

// Fetch an inbox by its id
$inbox = Inbox::find(1234);

// Get all messages in an inbox
$messages = $inbox->messages();

// Get a message by its id
$message = $inbox->message(123456789);

// Get the last (newest) message in an inbox
$newestMessage = $inbox->lastMessage();

// Determine whether the inbox contains a message for a given recipient e-mail
$recipientReceivedMessage = $inbox->hasMessageFor('john@example.com');

// Get message recipients as an array
$recipients = $message->recipientEmails();
```
