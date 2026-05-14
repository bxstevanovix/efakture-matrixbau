#!/bin/bash
set -e

# Ensure Laravel storage directories exist and are writable
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs

# Publish production Vite build into the shared public/build volume.
if [ -d /var/www/build-artifacts ]; then
    BUILD_DIR=/var/www/html/public/build
    mkdir -p "$BUILD_DIR"
    find "$BUILD_DIR" -mindepth 1 -maxdepth 1 -exec rm -rf {} \;
    cp -a /var/www/build-artifacts/. "$BUILD_DIR"/
    chmod -R a+rX "$BUILD_DIR"
fi

# Only chown if running as root (production); in dev we run as appuser
if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data /var/www/html/storage
fi

# kreiraj symlink da se ne radi ručno
php artisan storage:link --force 2>/dev/null || true

exec "$@"
