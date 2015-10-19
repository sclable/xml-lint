Sclable XML Lint
================

A php tool to lint and validate xml files from the commandline.

[![Build Status](https://travis-ci.org/sclable/xml-lint.svg?branch=master)](https://travis-ci.org/sclable/xml-lint)

Usage
-----

### Installation with Composer

If you'd like to include this library in your project with [composer](https://getcomposer.org/), simply run:

    composer require "sclable/xml-lint":dev-master

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


Changelog
---------

For the changelog see the [CHANGELOG](CHANGELOG) file

License
-------

For the license and copyright see the [LICENSE](LICENSE) file
