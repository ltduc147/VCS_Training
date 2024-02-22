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
	args = parser.parse_args()

	parsed_url = urllib.parse.urlparse(args.url)
	host = parsed_url.netloc
	port = 80

	get_request = f"""GET / HTTP/1.1\r\nHost:{host}\r\nConnection:close\r\n\r\n"""

	response = send_request(host, port, get_request)

	# Find title
	match = re.search(r"<title>(.*?)</title>", response)

	if match:
		title = match.group(1)
		print(f"Title: {title}")
	else: 
		print("Title not found.")


if __name__ == "__main__":
	main()