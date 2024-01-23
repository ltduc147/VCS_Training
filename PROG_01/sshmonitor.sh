#!/bin/bash

# Requirement
: '
1. List danh sach cac phien dang nhap moi (So voi lan chay truoc).
2. Neu phat hien co phien dang nhap moi (So voi lan chay truoc) thi 
   gui mail cho quan tri vien.
'
# Function define
create_new_file(){
	if [ ! -e $1 ]; then
		touch $1;
	fi
}

# Parameter define
old_log="/home/ltduc147/VCS_Training/PROG_01/old_ssh_log"
current_log="/home/ltduc147/VCS_Training/PROG_01/current_ssh_log"
new_login='/home/ltduc147/VCS_Training/PROG_01/new_ssh_log'

create_new_file $old_log
create_new_file $current_log
create_new_file $new_login

# List the current ssh log
cat /var/log/auth.log | grep sshd > $current_log

# Find the new ssh login session
diff $current_log $old_log | awk -v year=$(date +%Y) '/.*sshd.*session opened.*/ {print "User " $5 " dang nhap thanh cong vao thoi gian " $4 " " $2 " " $3 " " year }' > $new_login

# Send mail to root@localhost if detect the new ssh login session
if [[ -s $new_login ]]; then
	cat $new_login | sendmail root@localhost
fiw


cat $current_log > $old_log