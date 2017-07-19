#!/bin/bash
# Hook triggered before every commit

./vendor/bin/php-cs-fixer fix src/

./vendor/bin/php-cs-fixer fix tests/

./vendor/bin/phpunit
