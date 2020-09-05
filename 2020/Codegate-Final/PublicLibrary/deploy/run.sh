#!/bin/bash

service nginx start
service php7.3-fpm start
service mysql start

mysql -u root < /tmp/init.sql
rm /tmp/init.sql

tail -f /dev/null
