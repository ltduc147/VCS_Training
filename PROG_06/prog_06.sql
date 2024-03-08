-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 08, 2024 lúc 09:19 AM
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
-- Cơ sở dữ liệu: `prog_06`
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `assignments`
--

INSERT INTO `assignments` (`id`, `teacher_id`, `title`, `description`, `file_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'PROG01: Shell Script', 'Assignment for practice with shell script', 'storage/assignments/1709832325_PROG01.docx', '2024-03-07 09:04:53', '2024-03-07 09:04:53'),
(2, 1, 'PROG02: Linux Programming', 'Assignment for practice programming in Linux environment', 'storage/assignments/1709832325_PROG02.docx', '2024-03-07 09:04:53', '2024-03-07 09:04:53'),
(3, 2, 'PROG03: SSH LOGGER', 'Assignment for build a ssh logger program to track the credentials of user', 'storage/assignments/1709832325_PROG03.docx', '2024-03-07 09:04:53', '2024-03-07 09:04:53'),
(6, 2, 'PROG04: HTTP Programming', 'Practice HTTP Programming', 'storage/assignments/1709832325_PROG04.docx', '2024-03-07 09:04:53', '2024-03-07 09:04:53');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `challenges`
--

INSERT INTO `challenges` (`id`, `teacher_id`, `title`, `hint`, `file_url`, `created_at`, `updated_at`) VALUES
(2, 1, 'Challenge 1', '- Tác giả là Thanh Hải\r\n- Được viết vào năm 1980\r\n- Thể thơ 5 chữ', 'storage/challenges/1709833345_mua xuan nho nho.txt', '2024-03-07 09:04:13', '2024-03-07 09:04:13'),
(3, 1, 'Challenge 2', '- Tác giả là Bằng Việt \r\n- Được viết vào năm 1963 \r\n- Có mặt trong chương trình SGK lớp 9\r\n- Một bài thơ với thể thơ tự do', 'storage/challenges/1709833396_bep lua.txt', '2024-03-07 09:04:13', '2024-03-07 09:04:13'),
(6, 1, 'CHALLENGE 3', 'aaaaaaaaa\r\naaaaaaaaa\r\naaaaaaaaa', 'storage/challenges/1709838759_mua xuan nho nho.txt', '2024-03-07 12:12:00', '2024-03-07 12:12:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message_content`, `created_at`, `updated_at`) VALUES
(2, 3, 1, 'Khang just edit the', '2024-03-07 09:33:37', '2024-03-07 12:14:20'),
(4, 4, 1, 'Please contact me', '2024-03-07 09:33:37', '2024-03-07 09:33:37'),
(5, 6, 1, 'Please let me know when you receive this message', '2024-03-07 09:33:37', '2024-03-07 09:33:37'),
(6, 3, 2, 'User 3 send to User 2', '2024-03-07 09:33:37', '2024-03-07 09:33:37'),
(7, 1, 2, 'Here is the second message user 1 send to user 2.', '2024-03-07 09:33:37', '2024-03-07 02:34:01'),
(8, 1, 3, 'Hello Khang, My name is Duc. Edit', '2024-03-07 09:33:37', '2024-03-07 09:33:37'),
(9, 1, 2, 'Sorry, i just edit it and exit and edit.', '2024-03-07 09:33:37', '2024-03-07 10:36:35'),
(10, 2, 1, 'Hello, second message to Le TRung Duc, after edit', '2024-03-07 09:33:37', '2024-03-07 09:33:37'),
(12, 1, 4, 'Hello Huy, can you make contact with me?', '2024-03-07 09:33:37', '2024-03-07 09:33:37'),
(14, 1, 6, 'Hello Nguyen Van A. I just Edit it', '2024-03-07 12:08:56', '2024-03-07 12:09:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `submissions`
--

INSERT INTO `submissions` (`id`, `student_id`, `assignment_id`, `file_url`, `created_at`, `updated_at`) VALUES
(2, 3, 2, 'storage/submissions/1709837088_duclt22_prog02.docx', '2024-03-07 09:02:30', '2024-03-07 11:44:49'),
(3, 3, 3, 'storage/submissions/1709833176_duclt22_prog03.docx', '2024-03-07 09:02:30', '2024-03-07 09:02:49'),
(5, 4, 1, 'storage/submissions/1709833154_PROG01.docx', '2024-03-07 09:02:30', '2024-03-07 09:02:49'),
(6, 4, 2, 'storage/submissions/1709833387_PROG04.docx', '2024-03-07 09:02:30', '2024-03-07 09:02:49'),
(7, 6, 1, 'storage/submissions/1709833391_PROG05.docx', '2024-03-07 09:02:30', '2024-03-07 09:02:49'),
(9, 3, 1, 'storage/submissions/1709836617_duclt17_prog01.docx', '2024-03-07 11:36:57', '2024-03-07 11:36:57'),
(10, 3, 6, 'storage/submissions/1709838901_duclt22_prog04.docx', '2024-03-07 12:15:01', '2024-03-07 12:15:01');

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
  `role` enum('student','teacher') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone_number`, `avt_url`, `role`, `created_at`, `updated_at`) VALUES
(1, 'teacher1', '123456a@A', 'Lê Trung Đức', 'teacher1@gmai.com', '0356835890', 'storage/avatars/1709832325_Chandung.jpg', 'teacher', '2024-03-07 08:59:33', '2024-03-07 12:06:30'),
(2, 'teacher2', '123456a@A', 'Nguyễn Hoài Nam', 'teacher2@gmail.com', '0364012031', 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8YXZhdGFyfGVufDB8fDB8fHww', 'teacher', '2024-03-07 08:59:33', '2024-03-07 09:00:51'),
(3, 'student1', '123456a@A', 'Nguyễn Phúc Khang', 'student1@gmail.com', '0933112030', 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YXZhdGFyfGVufDB8fDB8fHww', 'student', '2024-03-07 08:59:33', '2024-03-07 09:00:51'),
(4, 'student2', '123456a@A', 'Nguyễn Gia Huy', 'student2@gmail.com', '0123456789', 'storage/avatars/default.png', 'student', '2024-03-07 08:59:33', '2024-03-07 12:26:25'),
(6, 'student3', '123456a@A', 'Nguyễn Văn A', 'student3@gmail.com', '0123456789', 'storage/avatars/default.png', 'student', '2024-03-07 08:59:33', '2024-03-07 09:00:51');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
