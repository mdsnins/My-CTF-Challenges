# CSP
*CODEGATE 2020 Preliminary Web*

##Description
I made an simple echo service for my API practice.
If you find bug, please tell me!

[api.php](./deploy/src/api.php)

## Deployment
It doesn't include XSS bot! You should build your own bot (or manual test). XSS bot has to watch `__admin__.php`<br />
Before building docker container, you have to settings/domain as proper server host.<br />

## Exploit & Writeup
Vulnerability : let nginx to set CSP header + `php header()`'s special feature, md5 length-extension attack

PHP's `header()` function can set response's HTTP status code. For example, `header("HTTP/1.1 404")` will make a response have status code as 404. However, nginx's `add_header` only sets its header when reponse's status code is 200, 201, 204, 206, 301, 302, 304, 307, or 308. By two facts, we can destroy CSP header as well.

1. Get a single valid signature with API string by inspecting iframe.
2. Chain multiple API structure strings and generate new **corect** md5 signature by length-extension attack. At lease on API string have to call `header()` function with specially-crafted to set HTTP response code. (example: `header,HTTP/ 404, 11111`)
3. Report it to admin, and have fun!

You may watch my [solve.py](./solve.py) which was running thorugh CTF to test challenge is on. 
