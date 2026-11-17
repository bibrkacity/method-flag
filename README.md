# Laravel. The test of the using the own attribute for method's flag

A method flag is a parameter used to control a method's behavior. Let’s look at a simple scenario: an "AB" parameter in an `.env` file will determine whether a method should execute. This is useful, for example, for A/B testing or canary deployments.

The most straightforward approach is to simply insert an `if` statement into the method body to check the "AB" parameter. While this works, it’s not ideal—you would eventually have to manually go back and clean up these `if` statements once they are no longer needed. I’d like to propose an alternative using a custom attribute. It’s not perfect, but in my view, it helps keep the code clean. So, let’s get started.

Suppose we have a `NotificationService` with two methods: `sendSmsNotification(string $message)` and `sendEmailNotification(string $message)`. Our goal is to ensure that `sendSmsNotification()` only executes when the "AB" parameter in the `.env` file is set to `1` or `true`.

## Installation

1. Clone this repository (https://github.com/bibrkacity/method-flag.git) and `cd` into root folder ( *your-path*/method-flag)
2. Copy `.env.example` to `.env`
3. Run `composer install`
4. Run `php artisan serve`

Now you can run application at http://127.0.0.1:8000.

## Explanation of key points

1. Let’s write our own attribute: `app/Attributes/CheckFeatureFlag.php`
2. Let’s write a proxy generator: `app/Proxy/ProxyGenerator.php`.
3. Let’s modify the service provider for application `app/Providers/AppServiceProvider.php`.
