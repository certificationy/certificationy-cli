#!/bin/sh
set -xe

composer install --prefer-dist --no-progress --no-suggest

echo "Execute:"
echo "php certificationy.php start --training"

# keep the container alive
exec php-fpm