# Warn SPA users when client is outdated compared to server

[![Build Status](https://app.travis-ci.com/audunru/version-warning.svg?branch=master)](https://app.travis-ci.com/audunru/version-warning)
[![Coverage Status](https://coveralls.io/repos/github/audunru/version-warning/badge.svg?branch=master)](https://coveralls.io/github/audunru/version-warning?branch=master)
[![StyleCI](https://github.styleci.io/repos/432809067/shield?branch=master)](https://github.styleci.io/repos/407671897)

Laravel middleware to help you notify users who are using an outdated version of the SPA client.

When you deploy a new version of your SPA to your server, not all users will start using the new version immediately. Anyone who is already logged in and does not refresh the page, will continue to use the old client until they close the browser tab or refresh the page. This package is meant to help you notify users with an outdated client that they should reload in order to use the latest version.

Currently, the package gets its version info from a `package.json` file in the root folder. This means that you will have to update the version in the package.json file whenever you make a change that requires all clients to update.

# Installation

## Step 1: Install with Composer

```bash
composer require audunru/version-warning
```

## Step 2: Add middleware to your routes

Add the `VersionWarning` middleware to your API routes:

```php
'api' => [
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \audunru\VersionWarning\Middleware\VersionWarning::class,
],
```

## Step 3: Notify user when client is outdated

### Alternative A: Compare versions server side

On the client side, whenever you make a fetch request to the server, add a `X-App-Version` header with a value of the client's current version. E.g.: `X-App-Version: 1.18.1`.

The `VersionWarning` middleware will compare the client version to the server version, and add a header called `X-Version-Warning` to the response if the versions do not match.

On the client side, you should check for the presence of the `X-Version-Warning` header. If it is present, the user should be notified that a reload is necessary so that the latest client assets (JavaScript and CSS) can be loaded.

### Alternative B: Compare versions client side

The `VersionWarning` header adds a header called `X-App-Version` to the response. On the client side, you can compare this version to the client version and notify the user in case of a mismatch.

# Configuration

Publish the configuration file by running:

```php
php artisan vendor:publish --tag=version-warning-config
```

# Development

## Testing

Run tests:

```bash
composer test
```
