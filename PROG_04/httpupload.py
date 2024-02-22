import socket
import argparse 
import re
import urllib.parse
import mimetypes
import os
import json

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

		return response.decode()

	finally:
		# Close the socket
		client_socket.close()


def main():
	parser = argparse.ArgumentParser()
	parser.add_argument('--url', default="http://web1.com", help='url of the website')
	parser.add_argument('--user', default="user", help='username of user')
	parser.add_argument('--password', default="pass", help='password of user')
	parser.add_argument('--local-file', default="file", help='path of local file')

	args = parser.parse_args()

	parsed_url = urllib.parse.urlparse(args.url)
	user = args.user
	pwd = args.password
	host = parsed_url.netloc
	port = 80

	# Send login request to get the admin cookie in order to use upload function
	body = f"log={urllib.parse.quote(user)}&pwd={urllib.parse.quote(pwd)}"
	login_request = (
		f"POST /wp-login.php HTTP/1.1\r\n" 
		f"Host: {host}\r\n" 
		f"Content-Type: application/x-www-form-urlencoded\r\n" 
		f"Content-Length: {len(body)}\r\n" 
		f"Connection: close\r\n" 
		f"\r\n" 
		f"{body}"
	).encode()

	#print(login_request)
	response = send_request(host, port, login_request)
	#print(response)

	# Authentication successfully
	if "302 Found" in response:

		# Get admin cookie
		match = re.search(r"Set-Cookie: (.*?); path=/wp-admin;", response)
		admin_cookie = match.group(1)

		# Create the body of upload request (include _wpnonce param)

		boundary = "---------------------------7774532971422707003465632897"
		# 1. Get _wpnonce param of upload function from media-new.php page
		get_media_new = (
			f"GET /wp-admin/media-new.php HTTP/1.1\r\n"
			f"Host: {host}\r\n"
			f"Cookie: {admin_cookie}\r\n"
			f"Connection: close\r\n"
			f"\r\n"
			f""
		).encode()
		
		response = send_request(host, port, get_media_new)
		_wpnonce = re.search(r'name="_wpnonce" value="(.*?)"', response).group(1)

		# 2. Read the file content
		file_path = args.local_file
		file_name = os.path.basename(file_path)
		f = open(file_path, "rb")
		file_content = f.read()

		# 3. Create full body
		body = (
			f"--{boundary}\r\n"
			f'Content-Disposition: form-data; name="name"\r\n'
			f"\r\n"
			f"{file_name}\r\n"
			f"--{boundary}\r\n"
			f'Content-Disposition: form-data; name="action"\r\n'
			f"\r\n"
			f"upload-attachment\r\n"
			f"--{boundary}\r\n"
			f'Content-Disposition: form-data; name="_wpnonce"\r\n'
			f"\r\n"
			f"{_wpnonce}\r\n"
			f"--{boundary}\r\n"
			f'Content-Disposition: form-data; name="async-upload"; filename="{file_name}"\r\n'
			f'Content-Type: {mimetypes.guess_type(file_name)[0]}\r\n'
			f"\r\n"
		).encode() + file_content + f"\r\n--{boundary}--\r\n".encode()

		# Create upload file request include the body
		upload_request = (
			f"POST /wp-admin/async-upload.php HTTP/1.1\r\n"
			f"Host: {host}\r\n"
			f"Referer: http://web1.com/wp-admin/upload.php\r\n"
			f"Content-Type: multipart/form-data; boundary={boundary}\r\n"
			f"Content-Length: {len(body)}\r\n"
			f"Connection: close\r\n"
			f"Cookie: {admin_cookie}\r\n"
			f"\r\n"
		).encode() + body

		#print(upload_request)

		response = send_request(host, port, upload_request)
		headers, body = response.split("\r\n\r\n", 1)
		json_body = json.loads(body)

		if json_body["success"] == True:
			url = json_body["data"]["url"].replace("\\", "")
			print(f'Upload success. File upload url: {url}')
		else:
			print("Upload failed")
	
	else:
		print("Authentication failed")


if __name__ == "__main__":
	main()