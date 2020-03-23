#!/bin/bash
docker build . -t web-renderer
docker run -d -it -p 80:80 --name rc web-renderer
