import socket
import argparse 
import re
import urllib.parse
import os

def send_request(host, port, request):
	# Create a TCP socket
	client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

	try:
		client_socket.connect((host, port))
        
		# Send the HTTP request
		client_socket.sendall(request)

		response = b""
		while True:
			data = client_socket.recv(4096)
			if not data:
				break
			response += data

		return response

	finally:
		# Close the socket
		client_socket.close()


def main():
	parser = argparse.ArgumentParser()
	parser.add_argument('--url', default="http://web1.com", help='url of the website')
	parser.add_argument('--remote-file', default="file", help='path of file in server')
	args = parser.parse_args()

	downloaded_dir = 'Downloads/' # Download directory
	parsed_url = urllib.parse.urlparse(args.url)
	host = parsed_url.netloc
	port = 80

	file_path = args.remote_file

	get_file = (
		f"GET {file_path} HTTP/1.1\r\n"
		f"Host: {host}\r\n"
		f"Connection: close\r\n"
		f"\r\n"
		f""
	).encode()

	response = send_request(host, port, get_file)
	#print(response)
	header, body = response.split(b"\r\n\r\n", 1)

	# File exist
	if "200 OK" in header.decode():
		# print(body)
		file_name = os.path.basename(file_path)
		with open(os.path.join(downloaded_dir, file_name), 'wb') as f:
			f.write(body)
		print(f"Image file size: {os.path.getsize(os.path.join(downloaded_dir, file_name))} bytes")
	else:
		print("Image file doesn't exist")

if __name__ == "__main__":
	main()