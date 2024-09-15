#!/bin/bash
# Hacer ping a la bd
export HTTP_HOST="${WP_URL:-localhost}"

while ! mysqladmin ping -h"$DB_HOST" --silent; do
    echo "database is not ready, sleeping..."
    sleep 5
done

wp config create --dbname="${MYSQL_DATABASE:-wordpress}" \
                --dbuser="${MYSQL_USER:-root}" \
                --dbpass="${MYSQL_PASSWORD:-root}" \
                --dbhost="${DB_HOST:-db}" \
                --path=/usr/src/wordpress \
                --allow-root \
                --force

wp core install --url="${WP_URL:-localhost}" \
                --title="${WP_TITLE:-My Site}" \
                --admin_user="${WP_ADMIN_USER:-admin}" \
                --admin_password="${WP_ADMIN_PASSWORD:-password}" \
                --admin_email="${WP_ADMIN_EMAIL:-admin@email.com}" \
                --path=/usr/src/wordpress \
                --skip-email \
                --allow-root

wp plugin activate login-awp --allow-root

apache2-foreground
