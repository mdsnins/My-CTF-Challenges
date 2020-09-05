FROM mysql:5.7

ENV MYSQL_DATABASE=pl \
    MYSQL_USER=user \
    MYSQL_PASSWORD=v3ryh4rdp455w0rd

RUN apt-get update
RUN apt-get install -y software-properties-common ca-certificates apt-transport-https wget
RUN apt-get install -y tzdata
RUN apt-get install -y nginx php-fpm php-mysqli

RUN rm /etc/nginx/sites-enabled/default
RUN rm /etc/mysql/my.cnf
COPY nginx.conf /etc/nginx/sites-enabled/nginx.conf
COPY my.cnf /etc/mysql/my.cnf

ADD init.sql /tmp/init.sql
ADD run.sh /tmp/run.sh
ADD prob_src /var/www/html

CMD ["/bin/bash", "/tmp/run.sh"]
