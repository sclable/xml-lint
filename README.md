Sclable XML Lint
================

A php tool to lint and validate xml files from the commandline.

[![Build Status](https://travis-ci.com/sclable/xml-lint.svg?branch=main)](https://travis-ci.com/sclable/xml-lint) [![Latest Stable Version](https://poser.pugx.org/sclable/xml-lint/v)](//packagist.org/packages/sclable/xml-lint) [![Total Downloads](https://poser.pugx.org/sclable/xml-lint/downloads)](//packagist.org/packages/sclable/xml-lint) [![License](https://poser.pugx.org/sclable/xml-lint/license)](//packagist.org/packages/sclable/xml-lint)

XML Lint checks the syntax of any xml files and validates the file against the XSD schema defined in the file.

Usage
-----

### Installation with Composer

If you'd like to include this library in your project with [composer](https://getcomposer.org/), simply run:

    composer require "sclable/xml-lint"

### Command Line Usage

To lint a single xml file:

    vendor/bin/xmllint path/to/file.xml

To lint a directory and all its subdirectories:

    vendor/bin/xmllint path/to/dir
    
#### Help

`xmllint` has built in cli help screen:

    vendor/bin/xmllint --help

#### Options

* `-v` be verbose, display the filename of the current file to lint
* `-r 0` don't search recursive (if the argument is a directory)
* `-e name` exclude files or directories containing 'name'
* `-s` skip the xsd validation


Development
-----------

### Run tests

```shell
# check code style
php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run -v

# run tests
php vendor/bin/phpunit
php vendor/bin/behat
```

Using docker:

```shell
# Example
docker build -t xml-lint:php-8.4 --build-arg=PHP_VERSION="8.4" .

# PHP_VERSION: choose between 8.1, 8.2, 8.3 and 8.4
docker build -t xml-lint:php-8.1 --build-arg=PHP_VERSION="8.1" .
docker build -t xml-lint:php-8.2 --build-arg=PHP_VERSION="8.2" .
docker build -t xml-lint:php-8.3 --build-arg=PHP_VERSION="8.3" .
docker build -t xml-lint:php-8.4 --build-arg=PHP_VERSION="8.4" .

# Run with code style check
docker build -t xml-lint:php-8.4 --build-arg=PHP_VERSION="8.4" --build-arg=PHP_CS_FIXER=true .

# Use this image to run xml-lint:
cd tests/functional/_testdata
docker run -it --rm -v "$PWD":/var/src -w /var/src xml-lint:php-8.4 -r -v -- ./
```


Changelog
---------

For the changelog see the [CHANGELOG](CHANGELOG) file

License
-------

For the license and copyright see the [LICENSE](LICENSE) file
