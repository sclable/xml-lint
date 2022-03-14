ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli-alpine AS dependencies
ARG PHP_CS_FIXER
RUN apk add --update --no-cache zip unzip php-zip
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN addgroup -S php && adduser -S php -G php \
    && mkdir -p /usr/src/xml-lint \
    && chown php:php -R /usr/src/xml-lint
WORKDIR /usr/src/xml-lint
COPY --chown=php:php . ./
USER php
RUN composer install --prefer-dist -o -a -n --no-progress \
    &&  \
    if [[ -n "${PHP_CS_FIXER}" ]]; then \
      composer install --working-dir=tools/php-cs-fixer --prefer-dist -o -a -n --no-progress; \
    fi

FROM php:${PHP_VERSION}-cli-alpine AS test
ARG PHP_CS_FIXER
RUN addgroup -S php && adduser -S php -G php \
    && mkdir -p /usr/src/xml-lint \
    && chown php:php -R /usr/src/xml-lint

WORKDIR /usr/src/xml-lint
COPY --from=dependencies --chown=php:php /usr/src/xml-lint ./
USER php

RUN if [[ -n "${PHP_CS_FIXER}" ]]; then \
      php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --dry-run -v; \
    fi
RUN php vendor/bin/phpunit
RUN php vendor/bin/behat

FROM dependencies AS build_production
WORKDIR /usr/src/xml-lint

RUN rm -rf tools/ tests/ \
    && composer install --prefer-dist -o -a -n --no-progress --no-dev

FROM php:${PHP_VERSION}-cli-alpine AS production
WORKDIR /usr/src/xml-lint
COPY --from=build_production /usr/src/xml-lint ./
RUN ln -s /usr/src/xml-lint/bin/xmllint /usr/bin/xml-lint
WORKDIR /usr/src
ENTRYPOINT ["php", "/usr/src/xml-lint/bin/xmllint"]
CMD ["--help"]