# Upgrading

## From roots/sage-lib

This fork bumps illuminate from 5.6.x to 13.x and PHP from ^7.0 to >=8.3.

### Steps

1. Update your theme's `composer.json`:
   ```json
   "require": {
       "php": ">=8.3",
       "y-collective/sage-lib": "^13.2"
   }
   ```
2. Run `composer update y-collective/sage-lib --with-dependencies`
3. Ensure your server runs PHP 8.3+

### Potential breaking changes

- **Laravel 13.x** drops many deprecated APIs from earlier illuminate versions. If your
  theme or plugins interact directly with illuminate internals (e.g., `Illuminate\View\Factory`),
  review the [Laravel 13 upgrade guide](https://laravel.com/docs/13.x/upgrade).
- **PSR-12** replaces PSR-2. This only affects contributors, not consumers of the library.
- **`declare(strict_types=1)`** is now enforced in all source files. If you extend any
  Sage classes directly, ensure your method signatures are type-compatible.

## Within the fork (e.g., 13.x → 13.y)

Minor and patch versions are backward-compatible within the 13.x line.
Run `composer update y-collective/sage-lib` to stay current.
