import socket
import argparse 
import re
import urllib.parse

def send_request(host, port, request):
	# Create a TCP socket
	client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

	try:
		client_socket.connect((host, port))
        
		# Send the HTTP request
		client_socket.sendall(request.encode())

		response = b""
		while True:
			data = client_socket.recv(4096)
			if not data:
				break
			response += data

		return response.decode()

	finally:
		# Close the socket
		client_socket.close()


def main():
	parser = argparse.ArgumentParser()
	parser.add_argument('--url', default="http://web1.com", help='url of the website')
	parser.add_argument('--user', default="user", help='username of user')
	parser.add_argument('--password', default="pass", help='password of user')
	args = parser.parse_args()

	parsed_url = urllib.parse.urlparse(args.url)
	user = args.user
	pwd = args.password
	host = parsed_url.netloc
	port = 80

	# Create the body and full Post request
	body = f"log={urllib.parse.quote(user)}&pwd={urllib.parse.quote(pwd)}"

	post_request = f"""POST /wp-login.php HTTP/1.1
Host: {host}
Content-Type: application/x-www-form-urlencoded
Content-Length: {len(body)}
Connection: close

{body}"""

	post_request = post_request.replace("\n", "\r\n")
	#print(post_request)

	response = send_request(host, port, post_request)

	#print(response)

	# status code 302 mean authentication successfully
	if "302 Found" in response:
		print(f"User {user} dang nhap thanh cong")
	else:
		print(f"User {user} dang nhap that bai")


if __name__ == "__main__":
	main()