#!/bin/sh
# docker-entrypoint.sh

# Clear and warmup Symfony cache using real env vars
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

# Start supervisord
exec /usr/bin/supervisord -n