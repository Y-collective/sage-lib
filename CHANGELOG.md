# Changelog

## 13.2.0 (2025-06-30)

- Require PHP 8.3+
- Upgrade illuminate/view and illuminate/config to v13.17.0
- Upgrade PHPUnit to ^13.0, PHPCS to ^4.0 (PSR12)
- Add `declare(strict_types=1)` to all source files
- Add native typed properties and return types to all classes
- Fix `FileViewFinder::getPossibleViewFilesFromTemplates()` crash on empty input (PHP 8.0+)
- Fix `JsonManifest` silent failures on missing/invalid manifest files
- Remove unused `$instance` property from `Config`
- Fix `Blade` class docblock (wrongly labelled "BladeProvider")
- Change `Blade::compiler()` from `static` to instance-scoped resolver
- Replace `createMock()` with `createStub()` in tests (PHPUnit 13 compat)
- Add GitHub Actions CI workflow
- Add PHPUnit test suite (9 tests, 10 assertions)
- Add `.editorconfig`, `.gitattributes`, `phpunit.xml`
- Rewrite README with full documentation

## 8.0.0 (2023-??-??)

- Require PHP 7.4+
- Upgrade illuminate/view and illuminate/config to v8.61.0

## 7.0.0 (2022-??-??)

- Require PHP 7.1+
- Upgrade illuminate/view and illuminate/config to v5.6.x

## 5.6.0 (2018-??-??)

- Upgrade illuminate/view and illuminate/config to v5.6.x
- Initial fork from roots/sage-lib

---

Prior to this fork, see the [roots/sage-lib changelog](https://github.com/roots/sage-lib/releases).
