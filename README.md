# Warn SPA users when client is outdated compared to server

[![Build Status](https://github.com/audunru/version-warning/actions/workflows/validate.yml/badge.svg)](https://github.com/audunru/version-warning/actions/workflows/validate.yml)
[![Coverage Status](https://coveralls.io/repos/github/audunru/version-warning/badge.svg?branch=master)](https://coveralls.io/github/audunru/version-warning?branch=master)
[![StyleCI](https://github.styleci.io/repos/432809067/shield?branch=master)](https://github.styleci.io/repos/432809067)

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

### Alternative A: Compare X-App-Version header client side

When you make a fetch request to the server, add a `X-App-Version` header with a value of the client's current version. E.g.: `X-App-Version: 1.18.1`.

The `VersionWarning` middleware will compare the client version to the server version, and add a header called `X-Version-Warning` to the response if the versions do not match.

On the client side, you should check for the presence of the `X-Version-Warning` header. If it is present, the user should be notified that a reload is necessary so that the latest client assets (JavaScript and CSS) can be loaded.

### Alternative B: Compare X-App-Version header client side

The `VersionWarning` middleware adds a header called `X-App-Version` to the response. On the client side, you can compare this version to the client version and notify the user in case of a mismatch.

## Which version is the client running?

### Set client version during build

If you're using Laravel Mix, you can include the current version during build time.

Set the MIX_VERSION environment variable in your build script (you should install the [cross-var](https://www.npmjs.com/package/cross-var) and [cross-env](https://www.npmjs.com/package/cross-env) packages so that this works on both Mac/Windows):

```json
"production": "cross-var cross-env MIX_VERSION=$npm_package_version mix --production",
```

You can then use `process.env.MIX_VERSION` in your frontend code.

### Get client version from HTML meta tag

Add the following to your layout blade template:

```html
<meta name="app-version" content="{{ app_version() }}" />
```

In your frontend code, read the value of this meta tag.

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
