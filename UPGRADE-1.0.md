# Upgrading from 0.9 to 1.0

## Requirements

- PHP 8.2 or newer
- symfony/console and symfony/finder 7.4 or 8.1+

If your project can't meet these yet, stay on `^0.9`. It keeps working, but 0.9 is the
last version for PHP 8.1 and Symfony below 7.4 and won't get updates.

## Update

`^0.9` doesn't pick up 1.0, so change the constraint:

    composer require --dev sclable/xml-lint:^1.0

If you used `dev-master`, switch to `^1.0` (or `dev-main`). The `master` branch is gone.

## Command line

Options, arguments and exit codes stay the same, with two changes: `-r` and `-q`.

### `-r`

| Command    | 0.9                | 1.0                |
|------------|--------------------|--------------------|
| no `-r`    | recursive          | recursive          |
| `-r`       | **not recursive**  | recursive          |
| `-r 0`     | not recursive      | not recursive      |
| `-r false` | **recursive**      | not recursive      |
| `-r foo`   | recursive          | **error**          |

If you used a bare `-r` to lint only the top directory, use `-r 0` instead. It works the
same in both versions.

### `-q`

`-q` printed nothing at all. It now prints the files with problems and their errors,
like Symfony's help text for `-q` says. Use `--silent` if you want no output.

### Other

The header line now shows the installed version (e.g. `Sclable Xml Lint 1.0.0`) instead
of `Sclable Xml Lint dev`. This only matters if you parse the output.

New: the tool is also installed as `vendor/bin/xml-lint`. `vendor/bin/xmllint` keeps working.

## PHP API

The PHP classes were never meant to be used directly. From 1.0 they are `final`,
marked `@internal` and use native types, and `ValidationInterface::validateFile()`
returns `bool`. Code that extends or calls them may break in any release. If you need
something from PHP, please [open an issue](https://github.com/sclable/xml-lint/issues)
and tell us your use case.
