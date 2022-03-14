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
# Install dependencies
docker run -it --rm -v  "$PWD":/usr/src/xml-lint -w /usr/src/xml-lint -v ${COMPOSER_HOME:-$HOME/.composer}:/tmp --user $(id -u):$(id -g) composer install --ignore-platform-reqs --no-scripts
docker run -it --rm -v  "$PWD":/usr/src/xml-lint -w /usr/src/xml-lint/tools/php-cs-fixer -v ${COMPOSER_HOME:-$HOME/.composer}:/tmp --user $(id -u):$(id -g) composer install --ignore-platform-reqs --no-scripts

# Run code style check
docker run -it --rm -v "$PWD":/usr/src/xml-lint -w /usr/src/xml-lint php:7.4-cli php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run -v

# Run tests
docker run -it --rm -v "$PWD":/usr/src/xml-lint -w /usr/src/xml-lint --user $(id -u):$(id -g) php:7.4-cli php vendor/bin/phpunit
docker run -it --rm -v "$PWD":/usr/src/xml-lint -w /usr/src/xml-lint --user $(id -u):$(id -g) php:7.4-cli php vendor/bin/behat
```


Changelog
---------

For the changelog see the [CHANGELOG](CHANGELOG) file

License
-------

For the license and copyright see the [LICENSE](LICENSE) file
