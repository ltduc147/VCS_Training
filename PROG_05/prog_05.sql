-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 05, 2024 lúc 08:10 AM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `prog_05`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `teacher_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `assignments`
--

INSERT INTO `assignments` (`id`, `teacher_id`, `title`, `description`, `file_url`, `created_time`) VALUES
(1, 1, 'PROG01: Shell Script', 'Assignment for practice with shell script', 'uploads/assignments/PROG01.docx', '2024-03-02 18:38:43'),
(2, 1, 'PROG02: Linux Programming', 'Assignment for practice programming in Linux environment', 'uploads/assignments/PROG02.docx', '2024-03-02 18:38:43'),
(3, 2, 'PROG03: SSH LOGGER', 'Assignment for build a ssh logger program to track the credentials of user', 'uploads/assignments/PROG03.docx', '2024-03-02 18:38:43'),
(6, 2, 'PROG04: HTTP Programming', 'Practice HTTP Programming', 'uploads/assignments/PROG04.docx', '2024-03-03 16:29:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `challenges`
--

CREATE TABLE `challenges` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `hint` text NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `challenges`
--

INSERT INTO `challenges` (`id`, `teacher_id`, `title`, `hint`, `file_url`, `created_time`) VALUES
(2, 1, 'Challenge 1', '- Tác giả là Thanh Hải\r\n- Được viết vào năm 1980\r\n- Thể thơ 5 chữ', 'uploads/challenges/mua xuan nho nho.txt', '2024-03-04 14:59:06'),
(3, 1, 'Challenge 2', '- Tác giả là Bằng Việt \r\n- Được viết vào năm 1963 \r\n- Có mặt trong chương trình SGK lớp 9\r\n- Một bài thơ với thể thơ tự do', 'uploads/challenges/bep lua.txt', '2024-03-04 15:09:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message_content`) VALUES
(2, 3, 1, 'Khang just edit the message'),
(4, 4, 1, 'Please contact me'),
(5, 6, 1, 'Please let me know when you receive this message'),
(6, 3, 2, 'User 3 send to User 2'),
(7, 1, 2, 'Here is the second message user 1 send to user 2. And the additional content after edit and edit again'),
(8, 1, 3, 'Hello Khang, My name is Duc. Edit'),
(9, 1, 2, 'Sorry, i just edit it and exit'),
(10, 2, 1, 'Hello, second message to Le TRung Duc, after edit'),
(12, 1, 4, 'Hello Huy, can you make contact with me?');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `submitted_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `submissions`
--

INSERT INTO `submissions` (`id`, `student_id`, `assignment_id`, `file_url`, `submitted_time`) VALUES
(2, 3, 2, 'uploads/submissions/duclt22_prog02.docx', '2024-03-04 08:53:16'),
(3, 3, 3, 'uploads/submissions/duclt22_prog03.docx', '2024-03-04 08:54:39'),
(4, 3, 1, 'uploads/submissions/duclt17_prog01.docx', '2024-03-04 09:24:47'),
(5, 4, 1, 'uploads/submissions/PROG01.docx', '2024-03-04 09:48:10'),
(6, 4, 2, 'uploads/submissions/PROG04.docx', '2024-03-04 09:49:12'),
(7, 6, 1, 'uploads/submissions/PROG05.docx', '2024-03-04 09:50:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `avt_url` varchar(255) NOT NULL DEFAULT 'uploads/avatars/default.png',
  `role` enum('student','teacher') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone_number`, `avt_url`, `role`) VALUES
(1, 'teacher1', '12345678', 'Lê Trung Đức', 'teacher1@gmai.com', '0432562487', 'uploads/avatars/1.jpg', 'teacher'),
(2, 'teacher2', '123456a@A', 'Nguyễn Hoài Nam', 'teacher2@gmail.com', '0364012031', 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8YXZhdGFyfGVufDB8fDB8fHww', 'teacher'),
(3, 'student1', '123456a@A', 'Nguyễn Phúc Khang', 'student1@gmail.com', '0933112030', 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YXZhdGFyfGVufDB8fDB8fHww', 'student'),
(4, 'student2', '123456a@A', 'Nguyễn Gia Huy', 'student2@gmail.com', '1234567890', 'uploads/avatars/default.png', 'student'),
(6, 'student3', '123456a@A', 'Nguyễn Văn A', 'student3@gmail.com', '0123456789', 'uploads/avatars/default.png', 'student');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
