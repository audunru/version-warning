# version-warning

Middleware package that warns SPA users when their client is running an outdated version by comparing a request header against the server-side app version and injecting response headers.

## Public API

- **Middleware**: `audunru\VersionWarning\Middleware\VersionWarning` — attach to routes that serve SPA API traffic; sets `X-App-Version` on every response and `X-Version-Warning: 1` when client and server versions differ.
- **Helper**: `app_version()` (global, from `src/helpers.php`) — resolves `VersionServiceContract` and returns the server-side version string.
- **Contract**: `audunru\VersionWarning\Contracts\VersionServiceContract` — `getAppVersion(): string` and `getClientVersion(Request): string`; bind a custom implementation in config to replace the default.

## Service Provider

Config key: `version-warning`. Published config: `config/version-warning.php`.

Artisan command: `version-warning:clear` — forgets the cache key (`app-version` by default).

**Binding logic** (see `src/VersionWarningServiceProvider.php`):
- If `version-warning.cache.enabled` is true (default), `VersionServiceContract` resolves to `CachingVersionService`, which wraps the configured inner service and stores the result with `Cache::rememberForever`.
- Otherwise `VersionServiceContract` resolves directly to `version-warning.services.version` (default: `NodeVersionService`).

**Cache invalidation**: `ClearCache` listener fires on any event listed in `cache.clear_on_events` and on `CommandFinished` for commands listed in `cache.clear_after_commands`. `CommandFinished` is always listened to; other events are merged from config at boot.

## Dependencies

- Internal: none
- External: no notable runtime deps beyond `laravel/framework` and `spatie/laravel-package-tools`

## Testing

Run from the package directory: `composer test`

Tests use Orchestra Testbench. `tests/TestCase.php` registers a `/test` route with `VersionWarning` middleware and sets dummy `clear_on_events`/`clear_after_commands` config values. File reads in tests are handled via `File::partialMock()` returning inline JSON.

Test coverage:
- `EnabledCacheTest` / `DisabledCacheTest` — header presence and cache behaviour
- `ClearCacheTest` — artisan command, event-triggered, and command-triggered cache clearing
- `NoPackageJsonTest` / `InvalidPackageJsonTest` — error handling paths
- `ThrowErrorTest` — `errors.throw = true` behaviour
- `HelpersTest` — `app_version()` helper

## Conventions / Gotchas

- The default `NodeVersionService` reads `package.json` at `base_path('package.json')` and returns its `version` field. Swap via `version-warning.services.version` to use a different source.
- The client version is read from the same header name as the app-version response header (`X-App-Version`). The SPA must echo back the last-known version on each request for comparison to work.
- `errors.throw` defaults to `false`; version detection failures are logged silently. Set to `true` and handle `VersionWarningException` in `Handler.php` if you need hard failures.
- Cache is `rememberForever`; use `cache.clear_on_events` or `cache.clear_after_commands` (e.g. after a deploy command) or call `version-warning:clear` to bust it.
