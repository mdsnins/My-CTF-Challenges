#!/bin/bash

service nginx start
service php7.2-fpm start

php /var/www/html/__init.php
rm /var/www/html/__init.php

chown www-data:www-data /var/www/csp.db
chmod 775 /var/www/csp.db

/bin/bash
