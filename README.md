## Synopsis

At the top of the file there should be a short introduction and/ or overview that explains **what** the project is. This description should match descriptions added for package managers (Gemspec, package.json, etc.))

## Installation

We distribute a [PHP Archive (PHAR)](http://php.net/phar) that has all required (as well as some optional) dependencies of PHPUnit bundled in a single file:

    wget https://phar.phpunit.de/phpunit.phar
    chmod +x phpunit.phar
    mv phpunit.phar /usr/local/bin/phpunit

## Tests

Describe and show how to run the tests with code examples.

    phpunit --colors --debug tests/FizzBuzzSpecificationTest
    phpunit --colors --debug tests/FizzBuzzApplicationTest

