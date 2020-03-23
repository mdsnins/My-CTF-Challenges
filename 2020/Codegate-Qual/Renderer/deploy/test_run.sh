#!/bin/bash
docker build . -t web-renderer
docker run --rm -it -p 80:80 web-renderer /bin/bash
