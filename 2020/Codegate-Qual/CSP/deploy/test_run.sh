#!/bin/bash
docker build . -t web-csp
docker run --rm -it -p 80:80 web-csp /bin/bash
