FROM wordpress:apache

RUN apt-get update && apt-get install -y \
    less \
    mariadb-client \
    sudo \
    && rm -rf /var/lib/apt/lists/*

ADD https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar .

RUN chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

WORKDIR /usr/src/wordpress

RUN set -eux; \
    find /etc/apache2 -name '*.conf' -type f -exec sed -ri  \
    -e "s!/var/www/html!$PWD!g" \
    -e "s!Directory /var/www/!Directory $PWD!g" '{}' +; \
    cp -s wp-config-docker.php wp-config.php

COPY trunk/ ./wp-content/plugins/login-awp/

COPY deployment/init.sh /usr/local/bin/init.sh

RUN chmod +x /usr/local/bin/init.sh && \
    chmod 777 -R /usr/src/wordpress/wp-content/uploads/

ENTRYPOINT ["/usr/local/bin/init.sh"]
