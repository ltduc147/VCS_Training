#!/bin/bash

echo -e "[Thong tin he thong]\n"

#1. Ten may, Ten ban phan phoi
echo "Ten May: $(hostname)"
echo "Ten ban phan phoi: $(lsb_release -d | cut -f2)"

#2. Phien ban he dieu hanh
echo "Phien ban he dieu hanh: $(lsb_release -r | cut -f2 )"

#3. Thong in CPU (ten, 32-64 bit, toc do)
echo "Ten CPU: $(lscpu | grep 'Model name' | cut -d: -f2 | awk '{$1=$1;print}')"
echo "Kien truc CPU: $(lscpu | grep 'Architecture' | cut -d: -f2 | awk '{$1=$1;print}')"
echo "Toc do CPU: $(cat /proc/cpuinfo | grep 'MHz'| cut -d: -f2 | head -n 1)"

#4. Thong tin bo nho vat li
echo "Tong bo nho vat ly: $(free -m | awk 'NR==2 {print $2}') MB"

#5. Thong tin o dia con trong
echo -e "O dia con trong: $(df -h / | awk 'NR==2 {print $4}')\n"

#6. Danh sach dia chi ip cua he thong
echo -e "Danh sach dia chi IP:\n$(ip addr | awk '/inet / {print $2}')\n"


#7. Danh sach user tren he thong
echo -e "Danh sach user:\n$(cat /etc/passwd | cut -d: -f1 | sort)\n"

#8. Thong tin cac tien trinh dang chay voi quyen root (sap xep theo abc)
echo -e "Cac tien trinh dang chay voi quyen root:\n$(ps -U root -F --sort=user) \n"


#9. Thong tin cac port dang mo (sap xep theo port tang dan)
echo -e "Danh sach cac port dang mo:$(cat /etc/services)\n"

#10. Danh sach cac thu muc tren he thong cho phep other co quyen ghi
echo -e "Danh sach cac thu muc cho phep other co quyen ghi:\n$(sudo find / -type d -perm -o=w)\n"

#11. Danh sach cac goi phan mem (ten goi, phien ben) duoc cai tren he thong
echo -e "Danh sach cac goi phan mem da cai dat:\n$(dpkg -l | awk '/ii/ {print $2,$3}')"


