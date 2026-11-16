# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

First stable release. The command line (options, arguments and exit codes) is the
public API, so breaking changes only happen in major versions from now on.
See [UPGRADE-1.0.md](UPGRADE-1.0.md) for how to upgrade.

### Added

- Support for PHP 8.5, based on [#44](https://github.com/sclable/xml-lint/pull/44) by @ischmittis.
- Support for Symfony 8.1 and newer.
- `xml-lint` command. It does the same as `xmllint`, but doesn't clash with the libxml2 tool of that name. The Docker image uses it too.
- `-r` accepts `yes`/`no`, `true`/`false`, `on`/`off` and `1`/`0`.

### Changed

- Requires PHP 8.2 or newer. New PHP minor versions no longer need a new release.
- Requires symfony/console and symfony/finder 7.4 or 8.1+.
- A bare `-r` now scans subdirectories. Before, it turned recursion off. An invalid value like `-r foo` is an error.
- The header line shows the installed version instead of `dev`.
- All PHP classes are `final` and `@internal`, use native types, and `ValidationInterface::validateFile()` returns `bool`.

### Removed

- Support for PHP 8.1.
- Support for Symfony 5.3, 5.4, 6.x and 7.0 to 7.3.

### Fixed

- `-r false`, `-r no` and `-r off` still scanned subdirectories. Only `-r 0` worked.
- `-e "a, b"` did not exclude `b` because of the space.
- An empty pattern like `-p " "` matched no files, so the lint passed without checking anything.
- "file not found" errors didn't show the path.
- A relative file name was printed as `/file.xml`.
- `--help --format=json` and `--format=xml` printed invalid output.
- `-q` printed nothing, not even the errors. It now prints only the error report.

## [0.9.0] - 2025-08-12

- Add support for PHP 8.4
- Drop support for PHP 8.0
- Update dependencies

## [0.8.0] - 2024-09-25

- Add support for Symfony 7
- Drop support for Symfony 4

## [0.7.0] - 2024-05-13

- Add support for PHP 8.3

## [0.6.0] - 2023-09-21

- Add support for PHP 8.2
- Drop support for PHP 7.4

## [0.5.0] - 2022-03-14

- Add support for PHP 8.1
- Drop support for PHP 7.3
- Chores: remove Travis integration

## [0.4.0] - 2020-12-17

- Add support for PHP 8
- Drop support for PHP 7.2
- Chores: Travis integration update

## [0.3.0] - 2020-04-27

- Add support for symfony console / finder 5.*
- Drop compatibility for PHP <7.2 and eol symfony versions
- Update test frameworks to latest versions

## [0.2.4] - 2018-03-22

- update dependencies to symfony/finder & symfony/console (include 4.*)

## [0.2.3] - 2017-04-04

- clean up composer file (move tests to autoload-dev)

## [0.2.2] - 2016-03-17

- update dependencies to symfony/finder & symfony/console

## [0.2.1] - 2015-12-03

- fix a bug where patterns were empty

## [0.2.0] - 2015-12-03

- validate xml files against XSD schemata (with skip option)
- pattern option

## [0.1.0] - 2015-11-20

- cli interface
- lint xml file
- lint files in a directory
- recursive option (default is 'yes')
- exclude option

[Unreleased]: https://github.com/sclable/xml-lint/compare/0.9.0...HEAD
[0.9.0]: https://github.com/sclable/xml-lint/compare/0.8.0...0.9.0
[0.8.0]: https://github.com/sclable/xml-lint/compare/0.7.0...0.8.0
[0.7.0]: https://github.com/sclable/xml-lint/compare/0.6.0...0.7.0
[0.6.0]: https://github.com/sclable/xml-lint/compare/0.5.0...0.6.0
[0.5.0]: https://github.com/sclable/xml-lint/compare/0.4.0...0.5.0
[0.4.0]: https://github.com/sclable/xml-lint/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/sclable/xml-lint/compare/0.2.4...0.3.0
[0.2.4]: https://github.com/sclable/xml-lint/compare/0.2.3...0.2.4
[0.2.3]: https://github.com/sclable/xml-lint/compare/0.2.2...0.2.3
[0.2.2]: https://github.com/sclable/xml-lint/compare/0.2.1...0.2.2
[0.2.1]: https://github.com/sclable/xml-lint/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/sclable/xml-lint/compare/0.1.0...0.2.0
[0.1.0]: https://github.com/sclable/xml-lint/releases/tag/0.1.0
