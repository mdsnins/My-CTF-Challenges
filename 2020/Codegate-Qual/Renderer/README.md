# Renderer
*CODEGATE 2020 Preliminary Web*

## Description
It's my first flask project with nginx.
Write your own message, and get flag!

[Dockerfile](./deploy/src/Dockerfile)
[run.sh](./deploy/settings/run.sh)

## Exploit & Writeup
Vulnerability : nginx alias off-by-slash file disclosure, HTTP header cr-lf injection, SSTI

Nginx's misconfiguration in `alias` let attacker to read arbitrary file under the parent directory. In this problem, `http://{{HOST}}/static../` will pointing parent directory of the static directory in server.

Also, python-2.7.16's urllib2.urlopen has cr-lf injection vulnerability at url parameter. This allows attacker to control raw HTTP packet to lead critical issues.

1. Leak source codes from the server using nginx's off-by-slash bug and information gathered from given  `Dockerfile` and `run.sh`
2. Inject `X-Forwarded-For` header to fake it as localhost
3. Since it also checks `User-Agent` string, which is set as `python/urlib-2.7.16` defaultly, to trick this, also inject `User-Agent` header. To successfully make this work, you have an extra cr-lf to make other default headers as body of HTTP packet

[solve.py](./solve.py) 
