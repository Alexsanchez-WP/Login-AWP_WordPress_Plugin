FROM wordpress

RUN apt update && apt upgrade -y && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

RUN wp config create --dbname=$WORDPRESS_DB_USER --dbuser=$WORDPRESS_DB_USER --dbpass=$WORDPRESS_DB_PASSWORD

RUN wp core install --url=$URL --title=$TITLE --admin_user=$ADMIN_USER --admin_password=$ADMN_PASSWORD --admin_email=$ADMIN_EMAIL

RUN cp /var/www/html/wp-content/wp-config-docker.php /var/www/html/wp-content/wp-config.php

COPY . /var/www/html/wp-content/plugins/login_awp

RUN wp plugin activate login_awp

