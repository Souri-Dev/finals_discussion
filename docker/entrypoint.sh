#!/bin/sh
set -e

envsubst '${PORT}' < /etc/nginx/nginx.template.conf > /etc/nginx/nginx.conf

php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf