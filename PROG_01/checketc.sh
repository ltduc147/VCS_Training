#!/bin/bash

# requirement
: '
1. Kiem tra thu muc /etc co file nao duoc tao moi (so voi lan chay truoc). Neu co hien 
   thi thong tin file va hien thi 10 dong dau (neu la file text)
2. Kiem tra thu muc /etc co file nao bi thay doi. Neu co hien thi ten file
3. Kiem tra thu muc /etc co file nao bi xoa. Neu co hien thi ten file
4. Day log ra file /var/log/checketc.log
5. Gui email cho quan tri vien root@localhost (Can cau hinh SMTP)
' 

# Parameter defined
#ETC_LOG_FILE = "/var/log/checketc.log"
#ETC_PATH = "/etc"

ETC_LOG_FILE="/var/log/checketc.log"
ETC_PATH="/etc"

old_etc='/home/ltduc147/VCS_Training/PROG_01/old_etc'
current_etc='/home/ltduc147/VCS_Training/PROG_01/current_etc'
new_and_modified='/home/ltduc147/VCS_Training/PROG_01/new_and_modified_file'
tmp_log='/home/ltduc147/VCS_Training/PROG_01/etc_temp_log'


# Function define
create_new_file(){
	if [[ ! -e $1 ]]; then
		touch $1;
	fi
}

find_new_file(){
	for i in $(cat $new_and_modified); do 
		if [[ ! $(grep -w $i $old_etc) ]]; then
			echo -e "$i\n" >> $tmp_log

			if [[ -n "$(file $i | grep 'text')" ]]; then
				head $i -n 10 >> $tmp_log
			fi

			echo -e "\n\n" >> $tmp_log

		fi
	done
}

find_modified_file(){
	for i in $(cat $new_and_modified); do 
		if [[ $(grep -w $i $old_etc) ]]; then
			echo $i >> $tmp_log
		fi
	done
}

find_deleted_file(){
	for i in $(cat $old_etc); do
		if [[ ! $(grep -w $i $current_etc) ]]; then
			echo $i >> $tmp_log
		fi 
	done
}


# Create the log file for the first time
create_new_file $ETC_LOG_FILE

create_new_file $old_etc
create_new_file $current_etc
create_new_file $new_and_modified
create_new_file $tmp_log

echo -e "[Log checketc - $(date +%T) $(date +%D)]\n" > $tmp_log

find $ETC_PATH -type f > $current_etc
find $ETC_PATH -type f -cmin -30 > $new_and_modified

# Requirement 1
echo -e "=== Danh sach file tao moi ===\n" >> $tmp_log
find_new_file

# Requirement 2
echo -e "\n=== Danh sach file sua doi ===\n" >> $tmp_log
find_modified_file

# Requirement 3
echo -e "\n=== Danh sach file bi xoa ===\n" >> $tmp_log
find_deleted_file

# Requirement 4
cat $tmp_log >> $ETC_LOG_FILE

# Requirement 5
sendmail root@localhost < $tmp_log


cat $current_etc > $old_etc 