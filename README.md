# A bunch of security focused features 💪

This packages provides you with many security features for your laravel or lumen proect

## Installation

You can install the package via composer:

```bash
composer require mawuekom/laravel-security-features
```

### Laravel <br/>

Go to **config/app.php**, and add this in the providers key

```php
'providers' =>
    ...
    Mawuekom\SecurityFeatures\SecurityFeaturesServiceProvider::class
    ...
];
```
<br/>

Publish package config

```bash
php artisan vendor:publish --provider="Mawuekom\SecurityFeatures\SecurityFeaturesServiceProvider"
```

### Lumen <br/>

Go to **`bootstrap/app.php`**, and add this in the specified key

```php

$app ->register(Mawuekom\SecurityFeatures\SecurityFeaturesServiceProvider::class);

```
## Middleware

Modern security Middleware has been added to ensure our APIs or projects are a
little more hardened than a default install.

The internet is a dangerous place, and when we are non-security minded developers, we
often make mistakes that could easily be avoided.

### App ID

This is the Identifier someone needs to send through to access your
application.

This allows you to add a layer of annoyance to endpoints that do not need
authentication, for example, endpoints that provide certain variables to
applications, but that you do not want to hardcode into your applications.

It's also handy for providing different configuration information from a
common endpoint based on the application in question, useful for things
like white labels.

Set `APP_ID` in your .env and wrap your route in the middleware.

Example HTTP Header

```text
App: 609DDCAC-6863-460F-8A3C-8ADFBDD4CFA6
```

 -  **`Laravel`**

 Go to **`App\Http\Kernel.php`**, and add this to `$routeMiddleware`

 ```php
protected $routeMiddleware = [
    ...
    'app_id' => Mawuekom\SecurityFeatures\Http\Middleware\AppIDMiddleware::class
    ...
];
```

 -  **`Lumen`**

 Go to **`bootstrap/app.php`**, and add this to `$app->routeMiddleware`

 ```php
$app ->routeMiddleware([
    ...
    'app_id' => Mawuekom\SecurityFeatures\Http\Middleware\AppIDMiddleware::class
    ...
]);
```

### Registration Access Key

Use this to limit access to registration endpoints to add a layer of
annoyance.

This is useful for allowing endpoints for newsletter signups, etc., to
know a key before being able to submit.

Set `REGISTRATION_ACCESS_KEY` in your .env and wrap your route in the
middleware.

Example HTTP Header

```text
Registration-Access-Key: 7D88D948-9E50-4EEB-A406-B3A87846918B
```

 -  **`Laravel`**

 Go to **`App\Http\Kernel.php`**, and add this to `$routeMiddleware`

 ```php
protected $routeMiddleware = [
    ...
    'register' => Mawuekom\SecurityFeatures\Http\Middleware\RegisterKeyMiddleware::class
    ...
];
```

 -  **`Lumen`**

 Go to **`bootstrap/app.php`**, and add this to `$app->routeMiddleware`

 ```php
$app ->routeMiddleware([
    ...
    'register' => Mawuekom\SecurityFeatures\Http\Middleware\RegisterKeyMiddleware::class
    ...
]);
```

### Throttle

Allows you to set limits per route as to how many requests may happen.

This is useful for mitigating DDoS, Brute Force, and Flooding style
attacks.

`'throttle:3,1'` means 3 requests per minute. `'throttle:300,1'` means
300.

Certain common routes have default throttles.

Responds with headers indicating how many requests are left on these
routes, and information about when bans expire.

Before Limit:

```text
X-RateLimit-Limit: 10
X-RateLimit-Remaining: 5
```

After Limit you get a `429 Too Many Requests`, and the Response Body contains

```text
Too many consecutive attempts. Try again in 5s
```

 -  **`Laravel`**

 Go to **`App\Http\Kernel.php`**, and add this to `$routeMiddleware`

 ```php
protected $routeMiddleware = [
    ...
    'throttle' => Mawuekom\SecurityFeatures\Http\Middleware\ThrottleRequests::class
    ...
];
```

 -  **`Lumen`**

 Go to **`bootstrap/app.php`**, and add this to `$app->routeMiddleware`

 ```php
$app ->routeMiddleware([
    ...
    'throttle' => Mawuekom\SecurityFeatures\Http\Middleware\ThrottleRequests::class
    ...
]);
```

### Common Security Headers

Includes a set of Common security headers for browsers that support them.

Useful for defense against many different types of common attacks.
<br>

#### Content Security Policy

A good Content Security Policy helps to detect and mitigate certain types
of attacks, including Cross Site Scripting (XSS) and data injection
attacks.

Content Security Policy (CSP) requires careful tuning and precise
definition of the policy. If enabled, CSP has significant impact on the
way the browser renders pages (e.g., inline JavaScript disabled by
default and must be explicitly allowed in policy). CSP prevents a wide
range of attacks, including Cross-site scripting and other cross-site
injections.

```text
Content-Security-Policy: default-src 'none', connect-src 'self', 'upgrade-insecure-requests';
```

#### X-Content-Type-Options

Setting this header will prevent the browser from interpreting files as
something else than declared by the content type in the HTTP headers.

```text
X-Content-Type-Options: nosniff
```

#### X-Frame-Options

X-Frame-Options response header improve the protection of web
applications against Clickjacking. It declares a policy communicated from
a host to the client browser on whether the browser must not display the
transmitted content in frames of other web pages.

```text
X-Frame-Options: DENY
```

#### X-XSS-Protection

This header enables the Cross-site scripting (XSS) filter in your browser.

```text
X-XSS-Protection: 1; mode=block
```

#### HTTP Strict Transport Security (HSTS)

HTTP Strict Transport Security (HSTS) is a web security policy mechanism
which helps to protect websites against protocol downgrade attacks and
cookie hijacking. It allows web servers to declare that web browsers (or
other complying user agents) should only interact with it using secure
HTTPS connections, and never via the insecure HTTP protocol.

```text
Strict-Transport-Security: max-age=7776000; includeSubDomains
```

### No Cache Headers

Disables caching

```text
Cache-Control: no-cache, must-revalidate
```

### Server Header

Adds information about the server.

Useful for overriding and obscuring the name of the technology running
the web server, e.g. making Apache look like nginx, or for announcing
the application name and version.

```text
Server: APP_NAME (APP_VERSION)
X-Powered-By: APP_NAME (APP_VERSION)
```

 -  **`Laravel`**

 Go to **`App\Http\Kernel.php`**, and add this to `$routeMiddleware`

 ```php
protected $routeMiddleware = [
    ...
    'nocache'    => App\Http\Middleware\NoCache::class,
    'hideserver' => App\Http\Middleware\ServerHeader::class,
    'security'   => App\Http\Middleware\SecurityHeaders::class,
    'csp'        => App\Http\Middleware\ContentSecurityPolicyHeaders::class,
    'hsts'        => App\Http\Middleware\StrictTransportSecurityHeaders::class,
    ...
];
```

 -  **`Lumen`**

 Go to **`bootstrap/app.php`**, and add this to `$app->routeMiddleware`

 ```php
$app ->routeMiddleware([
    ...
    'nocache'    => App\Http\Middleware\NoCache::class,
    'hideserver' => App\Http\Middleware\ServerHeader::class,
    'security'   => App\Http\Middleware\SecurityHeaders::class,
    'csp'        => App\Http\Middleware\ContentSecurityPolicyHeaders::class,
    'hsts'        => App\Http\Middleware\StrictTransportSecurityHeaders::class,
    ...
]);
```

Requires APP_NAME and APP_VERSION set in the .env file.

### CORS

Adds support for Cross Origin Resource Sharing.

See `config/cors.php` for all options.

Defaults to:

```php
'supportsCredentials' => true,
'allowedOrigins' => ['*'],
'allowedHeaders' => [
  'Content-Type',
  'Content-Length',
  'Origin',
  'X-Requested-With',
  'Debug-Token',
  'Registration-Access-Key',
  'X-CSRF-Token',
  'App',
  'User-Agent',
  'Authorization'
],
'allowedMethods' => ['GET', 'POST', 'PUT',  'DELETE', 'OPTIONS'],
'exposedHeaders' => ['Authorization'],
'maxAge' => 0,
```

Should support OPTIONS Preflight with Authorization header.

 -  **`Lumen`**

 Go to **`bootstrap/app.php`**, and add this to specified keys

 ```php

 $app->middleware([
    ...
    Fruitcake\Cors\HandleCors::class,
    ...
]);

$app ->routeMiddleware([
    ...
    'cors' => Fruitcake\Cors\HandleCors::class
    ...
]);
```

## Contributing

Please be brutally critical of this in the interest of improving the
security.

Feel free to contribute back.

I'm sure there are hundreds of ways of improving upon this work. Let's
make the internet a safer place, together.

Security is everyone's problem.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
