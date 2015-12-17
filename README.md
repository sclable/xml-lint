Sclable XML Lint
================

A php tool to lint and validate xml files from the commandline.

[![Build Status](https://travis-ci.org/sclable/xml-lint.svg?branch=master)](https://travis-ci.org/sclable/xml-lint) [![Latest Stable Version](https://poser.pugx.org/sclable/xml-lint/v/stable)](https://packagist.org/packages/sclable/xml-lint) [![Total Downloads](https://poser.pugx.org/sclable/xml-lint/downloads)](https://packagist.org/packages/sclable/xml-lint) [![Latest Unstable Version](https://poser.pugx.org/sclable/xml-lint/v/unstable)](https://packagist.org/packages/sclable/xml-lint) [![License](https://poser.pugx.org/sclable/xml-lint/license)](https://packagist.org/packages/sclable/xml-lint)

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


Changelog
---------

For the changelog see the [CHANGELOG](CHANGELOG) file

License
-------

For the license and copyright see the [LICENSE](LICENSE) file
