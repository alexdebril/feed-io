#!/bin/bash
# Hook triggered before every commit

./vendor/bin/php-cs-fixer fix src/

./vendor/bin/phpunit
