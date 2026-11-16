Sclable XML Lint
================

A php tool to lint and validate xml files from the commandline.

[![CI](https://github.com/sclable/xml-lint/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/sclable/xml-lint/actions/workflows/ci.yml) [![Latest Stable Version](https://poser.pugx.org/sclable/xml-lint/v)](//packagist.org/packages/sclable/xml-lint) [![Total Downloads](https://poser.pugx.org/sclable/xml-lint/downloads)](//packagist.org/packages/sclable/xml-lint) [![License](https://poser.pugx.org/sclable/xml-lint/license)](//packagist.org/packages/sclable/xml-lint)

XML Lint checks the syntax of any xml files and validates the file against the XSD schema defined in the file.

Requirements
------------

* PHP 8.2 or newer with the `dom` and `libxml` extensions
* symfony/console and symfony/finder 7.4 or 8.1+

Usage
-----

### Installation with Composer

    composer require --dev sclable/xml-lint

### Command Line Usage

To lint a single xml file:

    vendor/bin/xml-lint path/to/file.xml

To lint a directory and all its subdirectories:

    vendor/bin/xml-lint path/to/dir

The tool is installed as `vendor/bin/xml-lint` and `vendor/bin/xmllint`. Both do the same.
`xmllint` is also the name of the libxml2 command line tool (`/usr/bin/xmllint`), so if
`vendor/bin` is on your `PATH`, use `xml-lint` to avoid mixing them up.

#### Help

    vendor/bin/xml-lint --help

#### Options

* `-r`, `--recursive[=yes|no]` scan subdirectories (default). Use `-r 0`, `-r false`, `-r no` or `-r off` to lint only the top directory
* `-e`, `--exclude=dir1,dir2` skip directories, by name or by path relative to the given directory
* `-p`, `--pattern=*.xml,*.svg` only lint files that match one of the patterns (default: `*.xml` and `*.xml.dist`)
* `-s`, `--skip-xsd` only check the syntax, don't validate against the XSD schema
* `-v` show each file name and its result
* `-q` only show errors
* `--silent` show nothing, only set the exit code

`-r`, `-e` and `-p` only apply when the argument is a directory.

#### Exit codes

* `0` all files are valid
* `1` at least one file has problems, or the arguments are invalid

### Public API

The command line (options, arguments and exit codes) is the public API and follows
[semantic versioning](https://semver.org/). The PHP classes are internal and can change
in any release.

Development
-----------

### Install

```shell
composer install
composer install --working-dir=tools/php-cs-fixer
composer install --working-dir=tools/phpstan
```

### Code style

```shell
# check
composer cs

# fix
composer cs:fix
```

### Static analysis

```shell
composer phpstan
```

### Run tests

```shell
# all tests
composer test

# unit tests (PHPUnit)
composer test:unit

# functional tests (Behat)
composer test:functional
```

Using docker:

```shell
# Example
docker build -t xml-lint:php-8.4 --build-arg=PHP_VERSION="8.4" .

# PHP_VERSION: choose between 8.2, 8.3, 8.4 and 8.5 (default: 8.4)
docker build -t xml-lint:php-8.2 --build-arg=PHP_VERSION="8.2" .
docker build -t xml-lint:php-8.3 --build-arg=PHP_VERSION="8.3" .
docker build -t xml-lint:php-8.4 --build-arg=PHP_VERSION="8.4" .
docker build -t xml-lint:php-8.5 --build-arg=PHP_VERSION="8.5" .

# Run with code style check
docker build -t xml-lint:php-8.4 --build-arg=PHP_VERSION="8.4" --build-arg=PHP_CS_FIXER=true .

# Use this image to run xml-lint:
cd tests/functional/_testdata
docker run -it --rm -v "$PWD":/var/src -w /var/src xml-lint:php-8.4 -v .
```


Changelog
---------

For the changelog see the [CHANGELOG](CHANGELOG.md) file

License
-------

For the license and copyright see the [LICENSE](LICENSE) file
