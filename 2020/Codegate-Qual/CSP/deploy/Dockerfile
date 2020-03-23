FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y nginx php php-sqlite3 php-fpm curl
RUN apt-get install -y git zip unzip

RUN rm /etc/nginx/sites-enabled/default
COPY settings/nginx.conf /etc/nginx/sites-enabled/default.conf

RUN rm -rf /var/www/html
COPY src /var/www/html

COPY settings/run.sh /tmp/run.sh
COPY settings/domain /tmp/domain
COPY settings/flag.php /var/www/flag.php

RUN chown -R www-data:www-data /var/www/
RUN chmod -R 775 /var/www
CMD ["/bin/bash", "/tmp/run.sh"]
