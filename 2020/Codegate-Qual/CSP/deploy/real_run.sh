#!/bin/bash
docker kill cspc
docker rm cspc
docker build . -t web-csp
docker run -d -it -p 80:80 --name cspc web-csp
