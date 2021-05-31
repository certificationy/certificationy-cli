#!/bin/sh
set -xe

# Detect the host IP
#export DOCKER_BRIDGE_IP=$(ip ro | grep default | cut -d' ' -f 3)

#if [ "$SYMFONY_ENV" = 'prod' ]; then
#	composer install --prefer-dist --no-dev --no-progress --no-suggest --optimize-autoloader --classmap-authoritative
#else
	composer install --prefer-dist --no-progress --no-suggest
#fi


echo "Execute:"
echo "php certificationy.php start --training"

# keep the container alive
exec php-fpm