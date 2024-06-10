-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 10 Haz 2024, 13:08:59
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `nutroplan`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `patient_id`, `date`, `time`, `description`, `created_at`) VALUES
(10, 11, 10, '2024-06-15', '18:30:00', 'sdfagsdaf', '2024-06-07 10:40:15'),
(12, 15, 17, '2024-06-13', '15:26:00', 'ewdx', '2024-06-09 10:24:06'),
(13, 17, 18, '2024-06-15', '15:33:00', '13221', '2024-06-09 11:49:26'),
(14, 17, 19, '2024-06-21', '04:55:00', '788778', '2024-06-09 11:49:35'),
(16, 18, 21, '2024-06-11', '09:30:00', 'Get weight', '2024-06-09 23:33:14'),
(17, 18, 22, '2024-06-12', '13:40:00', 'Fitness Trainer', '2024-06-09 23:33:45'),
(18, 18, 23, '2024-06-13', '10:35:00', 'Lose weight', '2024-06-09 23:33:58'),
(19, 18, 24, '2024-06-14', '07:40:00', 'Diabetic', '2024-06-09 23:35:41'),
(20, 18, 25, '2024-06-15', '10:30:00', 'Keep Fit', '2024-06-09 23:36:03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bodymetrics`
--

CREATE TABLE `bodymetrics` (
  `metric_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `fat_percentage` decimal(5,2) DEFAULT NULL,
  `muscle_mass` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `waist_circumference` decimal(5,2) DEFAULT NULL,
  `hip_circumference` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `bodymetrics`
--

INSERT INTO `bodymetrics` (`metric_id`, `patient_id`, `recorded_at`, `weight`, `height`, `fat_percentage`, `muscle_mass`, `bmi`, `waist_circumference`, `hip_circumference`) VALUES
(9, 17, '2024-06-09 10:19:45', 44.00, 444.00, 0.05, 3.00, 1.58, 44.00, 67.00),
(10, 17, '2024-06-09 10:20:11', 44.00, 444.00, 0.05, 3.00, 1.58, 44.00, 67.00),
(11, 18, '2024-06-09 11:08:25', 85.00, 185.00, 25.00, 35.00, 28.00, 35.00, 72.00),
(12, 19, '2024-06-09 11:49:10', 95.00, 175.00, 38.00, 25.00, 34.00, 37.00, 85.00),
(13, 20, '2024-06-09 12:10:15', 123.00, 199.00, 45.00, 45.00, 32.00, 32.00, 45.00),
(14, 21, '2024-06-09 23:31:09', 182.00, 182.00, 12.00, 35.00, 35.00, 42.00, 75.00),
(15, 22, '2024-06-09 23:31:39', 75.00, 185.00, 15.00, 65.00, 30.00, 34.00, 47.00),
(16, 23, '2024-06-09 23:32:14', 78.00, 163.00, 35.00, 67.00, 31.00, 34.00, 78.00),
(17, 24, '2024-06-09 23:34:55', 45.00, 150.00, 32.00, 27.00, 30.00, 17.00, 55.00),
(18, 25, '2024-06-09 23:35:30', 66.00, 166.00, 35.00, 50.00, 31.00, 34.00, 75.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mealplans`
--

CREATE TABLE `mealplans` (
  `meal_plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `mealplans`
--

INSERT INTO `mealplans` (`meal_plan_id`, `user_id`, `patient_id`, `plan_name`, `description`, `created_at`) VALUES
(1, 18, 20, 'ok', '-1\r\n-2\r\n-\r\n3\r\n-\r\n-4', '2024-06-09 21:04:24'),
(2, 18, 20, 'ok', '-1\r\n-2\r\n-\r\n3\r\n-\r\n-4', '2024-06-09 21:04:29');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `health_status` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `name`, `email`, `phone_number`, `age`, `gender`, `health_status`, `created_at`) VALUES
(10, 11, 'client hasan', 'clienthasan@gmail.com', '15649', 25, 'male', 'iyi', '2024-06-04 21:33:06'),
(17, 15, 'hasan', 'alpyildirim@gmai', '111111111111', 33, 'male', 'aaaa', '2024-06-09 10:19:45'),
(18, 17, 'test', 'test@hotmail.com', '123456789', 20, 'male', 'good', '2024-06-09 11:08:25'),
(19, 17, 'mrt', 'mrt@hotmail.com', '1593574682', 48, 'male', 'bad', '2024-06-09 11:49:10'),
(20, 18, 'test1', 'test1@gmail.com', '89778964545', 32, 'male', 'good', '2024-06-09 12:10:15'),
(21, 18, 'Hasan', 'asdasd@asdas.com', '12321', 26, 'male', 'good', '2024-06-09 23:31:09'),
(22, 18, 'Murat', 'asddsa@asdas.com', '12321312', 36, 'male', 'Good', '2024-06-09 23:31:39'),
(23, 18, 'Alp', 'alp@asddsa.com', '345378', 36, 'male', 'good', '2024-06-09 23:32:14'),
(24, 18, 'Ayşe', 'sadas@mail.com', '231321', 21, 'female', 'Good', '2024-06-09 23:34:55'),
(25, 18, 'Fatma', 'test@mail.com', '654645', 38, 'female', 'Bad', '2024-06-09 23:35:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('paid','unpaid') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `patient_id`, `amount`, `status`, `created_at`) VALUES
(1, 18, 20, 1231321.00, 'unpaid', '2024-06-09 21:03:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reports`
--

INSERT INTO `reports` (`report_id`, `user_id`, `patient_id`, `report_type`, `created_at`, `file_path`) VALUES
(4, 18, 20, 'pdf', '2024-06-09 11:52:39', '../../uploads/reports/Defining_a_Healthy_Diet_Evidence_for_The_Role_of_C (1).pdf');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone_number`, `role`, `created_at`) VALUES
(10, 'admin', '$2y$10$NX5wNFmaAIRneMvymLaPnOszt7VVUktiCuuY3x/eRtfT8SITvl/ES', 'admin@example.com', NULL, 'admin', '2024-06-04 01:58:31'),
(11, 'doctor hasan', '$2y$10$NX5wNFmaAIRneMvymLaPnOszt7VVUktiCuuY3x/eRtfT8SITvl/ES', 'adminhasan@gmail.com', '', 'user', '2024-06-04 01:59:38'),
(12, 'admin alp', '$2y$10$0kEBfSj1sBo97Gvq/lmGtuhtR5ZL532phVWt0WjsdZaPc9iuWjB1u', 'adminalp@gmail.com', NULL, 'user', '2024-06-04 12:26:09'),
(14, 'muratcan', '$2y$10$6Q1N7SehEHGg255u4jtbseis1tUWfqFWy9qzHVWr40Enuo05BWopm', 'muratcan@gmail.com', NULL, 'user', '2024-06-07 10:26:28'),
(15, 'Alp Yıldırım', '$2y$10$McL9DZUAo9Mk0uL4MbxqF.tow0GLKtYN3yViIB9q9tYRgEk3f.Cd.', 'alpyildirim1907@gmail.com', '111111111111', 'user', '2024-06-09 10:19:03'),
(17, 'MuratCan', '$2y$10$4DGoBeKkD3kNHvpIxhUAq.4XOngW7.GqqFv.6luIbW.huFwQmO782', 'mcan@hotmail.com', NULL, 'user', '2024-06-09 11:07:36'),
(18, 'testAdmin', '$2y$10$YJ3dd.uAzp9jitXUmMhLY.fnzV2Bf/PYtwxHu8YigWdawP0qpMfFS', 'money42hunt@outlook.com', NULL, 'user', '2024-06-09 12:01:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `youtube_videos`
--

CREATE TABLE `youtube_videos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Tablo için indeksler `bodymetrics`
--
ALTER TABLE `bodymetrics`
  ADD PRIMARY KEY (`metric_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Tablo için indeksler `mealplans`
--
ALTER TABLE `mealplans`
  ADD PRIMARY KEY (`meal_plan_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Tablo için indeksler `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Tablo için indeksler `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `youtube_videos`
--
ALTER TABLE `youtube_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `bodymetrics`
--
ALTER TABLE `bodymetrics`
  MODIFY `metric_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `mealplans`
--
ALTER TABLE `mealplans`
  MODIFY `meal_plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `youtube_videos`
--
ALTER TABLE `youtube_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Tablo kısıtlamaları `bodymetrics`
--
ALTER TABLE `bodymetrics`
  ADD CONSTRAINT `bodymetrics_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Tablo kısıtlamaları `mealplans`
--
ALTER TABLE `mealplans`
  ADD CONSTRAINT `mealplans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `mealplans_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Tablo kısıtlamaları `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Tablo kısıtlamaları `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Tablo kısıtlamaları `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Tablo kısıtlamaları `youtube_videos`
--
ALTER TABLE `youtube_videos`
  ADD CONSTRAINT `youtube_videos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
