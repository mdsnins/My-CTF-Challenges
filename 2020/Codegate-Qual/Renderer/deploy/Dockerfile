FROM python:2.7.16

ENV FLAG CODEGATE2020{SAMPLE_FLAG}

RUN apt-get update
RUN apt-get install -y nginx
RUN pip install flask uwsgi

ADD src/src /home/src
ADD settings/nginx-flask.conf /tmp/nginx-flask.conf

ADD src/static /home/static
RUN chmod 777 /home/static

RUN mkdir /home/tickets
RUN chmod 777 /home/tickets

ADD settings/run.sh /home/run.sh
RUN chmod +x /home/run.sh

ADD settings/cleaner.sh /home/cleaner.sh
RUN chmod +x /home/cleaner.sh

CMD ["/bin/bash", "/home/run.sh"]
