# Contributing

Thanks for considering a contribution!

## Development

```bash
git clone https://github.com/y-collective/sage-lib
cd sage-lib
composer install
```

## Running tests

```bash
composer test
```

This runs both PHPUnit and PHPCS (PSR12). Make sure all checks pass before submitting a PR.

## Coding standards

- PSR-12 for all PHP files
- PSR-4 autoloading
- Declare `strict_types=1` in every PHP file
- Use native type hints for properties, parameters, and return types
- Avoid PHPDoc annotations where native types suffice (prefer native typing)
- Write tests for any new functionality

## Pull request process

1. Fork the repo and create a branch from `main`
2. Add or update tests as needed
3. Run `composer test` – it must pass cleanly
4. Submit the PR with a clear description of the change

## Reporting issues

Report bugs at [github.com/y-collective/sage-lib/issues](https://github.com/y-collective/sage-lib/issues).
