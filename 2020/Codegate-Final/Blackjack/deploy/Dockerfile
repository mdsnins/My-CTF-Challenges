FROM ubuntu:18.04

RUN apt-get update
RUN apt-get install -y tzdata
RUN apt-get install -y nginx php7.2-fpm php-imagick php7.2-simplexml
RUN apt-get install -y imagemagick

RUN rm /etc/nginx/sites-enabled/default
RUN echo "env[FLAG] = \$FLAG" >> /etc/php/7.2/fpm/pool.d/www.conf
COPY nginx.conf /etc/nginx/sites-enabled/nginx.conf

ADD run.sh /tmp/run.sh
ADD prob_src /var/www/html

ENV FLAG CODEGATE2020{Lf1_C4n_M4k3_y0U_R1CH}
CMD ["/bin/bash", "/tmp/run.sh"]

