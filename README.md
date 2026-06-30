# Sage Lib

[![CI](https://github.com/y-collective/sage-lib/actions/workflows/ci.yml/badge.svg)](https://github.com/y-collective/sage-lib/actions/workflows/ci.yml)
[![Packagist](https://img.shields.io/packagist/vpre/y-collective/sage-lib.svg?style=flat-square)](https://packagist.org/packages/y-collective/sage-lib)
[![License](https://img.shields.io/packagist/l/y-collective/sage-lib.svg?style=flat-square)](LICENSE.md)

**Fork of [roots/sage-lib](https://github.com/roots/sage-lib) updated for PHP 8.3+ and Illuminate 13.x.**

Sage is a WordPress starter theme with a modern development workflow. This package provides the core library files (Blade rendering, asset manifest, config, and DI container) for Sage-based themes.

---

## Requirements

- PHP 8.3+
- Composer 2.x

## Installation

```bash
composer require y-collective/sage-lib
```

## Usage

### Blade templating

```php
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

$provider = new BladeProvider(null, [
    'view.paths' => ['path/to/views'],
    'view.namespaces' => [],
    'view.compiled' => 'path/to/cache',
]);
$provider->register();

$blade = new Blade($provider->getFactory());

// Render a view
echo $blade->render('template-name', ['key' => 'value']);

// Get the compiled path
echo $blade->compiledPath('template-name');

// Normalize a file path to a view name
echo $blade->normalizeViewPath('path/to/views/template-name.blade.php');
```

### Asset manifests

```php
use Roots\Sage\Assets\JsonManifest;

$manifest = new JsonManifest('path/to/mix-manifest.json', '/dist');

echo $manifest->get('app.js');      // 'app.abc123.js'
echo $manifest->getUri('app.js');   // '/dist/app.abc123.js'
```

### View fallback resolution

```php
use Roots\Sage\Template\FileViewFinder;

$finder = new FileViewFinder($filesystem, ['path/to/views']);
$finder->addExtension('blade.php');

// Hyphenated template names cascade as fallbacks:
// 'partials-sidebar' => tries 'partials-sidebar', then 'partials'
$files = $finder->getPossibleViewFiles('partials-sidebar');
```

## Development

```bash
# Run all checks
composer test

# Lint only
composer run lint

# Auto-fix lint issues
composer run fix
```

## What's included

| Class | Extends | Purpose |
|-------|---------|---------|
| `Roots\Sage\Container` | `Illuminate\Container\Container` | DI container with terminating callback support |
| `Roots\Sage\Config` | `Illuminate\Config\Repository` | Configuration repository |
| `Roots\Sage\Assets\ManifestInterface` | — | Interface for cache-busting manifest readers |
| `Roots\Sage\Assets\JsonManifest` | `ManifestInterface` | Reads `mix-manifest.json` / similar files |
| `Roots\Sage\Template\Blade` | — | High-level Blade facade |
| `Roots\Sage\Template\BladeProvider` | `Illuminate\View\ViewServiceProvider` | Registers Blade services (filesystem, events, engine, finder, factory) |
| `Roots\Sage\Template\FileViewFinder` | `Illuminate\View\FileViewFinder` | Adds hyphen-delimited view fallback resolution |

## Changes from upstream

| Area | Upstream (`roots/sage-lib`) | This fork |
|------|-----------------------------|-----------|
| PHP requirement | `^7.0` | `>=8.3` |
| `illuminate/view` | `~5.6.x` | `^13.2.0` |
| `illuminate/config` | `~5.6.x` | `^13.2.0` |
| `composer/installers` | `~1.0` | `^2.3` |
| PHPUnit | none | `^13.0` |
| PHPCS standard | PSR2 | PSR12 |
| Type hints | PHPDoc annotations | Native typed properties + return types |
| `declare(strict_types=1)` | no | yes in all files |
| CI | Travis (PHP 7.1–7.3) | GitHub Actions (PHP 8.3–8.4) |
| Container | Empty extension | Added `terminating()` / `terminate()` methods |
| Tests | none | PHPUnit + PHPCS in CI |

## License

MIT. See [LICENSE.md](LICENSE.md).
