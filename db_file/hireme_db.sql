-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2024 at 07:35 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hireme_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `timing` time DEFAULT NULL,
  `day` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `employee_id`, `user_id`, `user_address`, `timing`, `day`, `description`, `created_at`, `status`) VALUES
(33, 55, 41, 'New Haji Camp', '23:11:00', '2024-09-18', 'I need custom-built furniture for my living room. This includes a coffee table and a set of shelves. Please use high-quality wood and follow the dimensions I’ll provide upon confirmation.', '2024-09-14 03:13:54', 'accepted'),
(34, 57, 47, 'clifton town', '11:19:00', '2024-09-28', 'I need for some electric boards repairings', '2024-09-14 16:58:41', 'pending'),
(35, 61, 47, 'new haji camp', '03:05:00', '2024-09-28', 'need for house renewations', '2024-09-14 17:05:39', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `city`) VALUES
(1, 'Korangi'),
(2, 'Gulshan-e-Iqbal'),
(3, 'Karimabad'),
(4, 'Kemari'),
(5, 'Clifton'),
(6, 'Saddar'),
(7, 'DHA'),
(8, 'Liaquatabad'),
(9, 'Nazimabad'),
(10, 'Orangi Town'),
(11, 'North Karachi'),
(12, 'Jamshed Town'),
(13, 'Gulistan-e-Johar'),
(14, 'PECHS'),
(15, 'Landhi'),
(16, 'Malir'),
(17, 'Pakistan Chowk'),
(18, 'Shah Faisal Town'),
(19, 'Surjani Town'),
(20, 'Gulberg');

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE `counter` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `discount_code` varchar(50) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `discount_code`, `discount_percentage`, `start_date`, `end_date`, `created_at`) VALUES
(1, 'SUMMER2024', 15.00, '2024-09-01 00:00:00', '2024-09-30 00:00:00', '2024-09-14 09:26:08'),
(15, 'summer24', 30.00, '2024-09-27 00:00:00', '2024-10-09 00:00:00', '2024-09-14 12:17:23'),
(16, 'win23', 50.00, '2024-09-14 00:00:00', '2024-10-24 00:00:00', '2024-09-14 18:23:46'),
(17, 'Winter2024', 56.00, '2024-09-30 00:00:00', '2024-10-30 00:00:00', '2024-09-15 01:53:37'),
(18, 'Autumn24', 70.00, '2024-09-28 00:00:00', '2024-10-29 00:00:00', '2024-09-15 01:54:03');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `skills`, `city_id`, `img`, `status`, `user_id`, `amount`) VALUES
(55, 'Carpenter', 4, 'uploads/images/emp.jpg', 'deactivate', 42, 400.00),
(57, 'Electrician', 6, 'uploads/images/avartar8.jpg', 'active', 43, 300.00),
(58, 'Tile Installer', 1, 'uploads/images/avatar6.jpg', 'inactive', 45, 600.00),
(59, 'Painter', 5, 'uploads/images/avartar5.jpg', 'inactive', 46, 650.00),
(60, 'Mechanic', 2, 'uploads/images/avartar3.jpg', 'active', 48, 500.00),
(61, 'Mason', 8, 'uploads/images/avatar1.jpg', 'activate', 50, 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `employee_work`
--

CREATE TABLE `employee_work` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hours_worked` decimal(5,2) NOT NULL,
  `work_status` enum('work_done','work_pending') DEFAULT 'work_pending',
  `payment_status` enum('paid','unpaid') DEFAULT 'unpaid',
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_work`
--

INSERT INTO `employee_work` (`id`, `booking_id`, `employee_id`, `user_id`, `hours_worked`, `work_status`, `payment_status`, `total_amount`, `created_at`) VALUES
(115, 33, 55, 41, 5.00, 'work_done', 'unpaid', 2000.00, '2024-09-14 03:15:54'),
(116, 35, 61, 47, 12.00, 'work_done', 'paid', 4800.00, '2024-09-14 17:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `feedback`, `created_at`) VALUES
(9, 47, 'Awesome Services...', '2024-09-14 16:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `googleapikey`
--

CREATE TABLE `googleapikey` (
  `google_id` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_messages`
--

CREATE TABLE `otp_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_messages`
--

INSERT INTO `otp_messages` (`id`, `user_id`, `otp_code`, `expires_at`, `created_at`) VALUES
(34, 41, '183074', '2024-09-14 11:07:48', '2024-09-14 08:57:51'),
(35, 51, '681749', '2024-09-14 18:41:45', '2024-09-14 16:31:57'),
(36, 47, '358792', '2024-09-14 18:42:58', '2024-09-14 16:33:02'),
(37, 51, '536870', '2024-09-14 18:43:32', '2024-09-14 16:33:36'),
(38, 47, '097354', '2024-09-14 18:46:12', '2024-09-14 16:36:17'),
(39, 47, '637580', '2024-09-14 18:52:35', '2024-09-14 16:42:40'),
(40, 47, '680423', '2024-09-14 18:53:46', '2024-09-14 16:43:50'),
(41, 47, '267943', '2024-09-14 18:55:03', '2024-09-14 16:45:09'),
(42, 47, '681930', '2024-09-14 18:56:27', '2024-09-14 16:46:30'),
(43, 47, '628715', '2024-09-14 18:57:34', '2024-09-14 16:47:38'),
(44, 50, '649038', '2024-09-14 18:59:54', '2024-09-14 16:49:59'),
(45, 51, '378510', '2024-09-14 19:01:50', '2024-09-14 16:51:53'),
(46, 47, '807152', '2024-09-14 19:05:19', '2024-09-14 16:55:24'),
(47, 50, '493750', '2024-09-14 19:09:33', '2024-09-14 16:59:38'),
(48, 47, '913507', '2024-09-14 19:10:56', '2024-09-14 17:01:01'),
(49, 50, '360958', '2024-09-14 19:12:13', '2024-09-14 17:02:18'),
(50, 47, '291854', '2024-09-14 19:13:14', '2024-09-14 17:03:19'),
(51, 50, '903165', '2024-09-14 19:16:06', '2024-09-14 17:06:11'),
(52, 47, '526419', '2024-09-14 19:19:03', '2024-09-14 17:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `stripe_charge_id` varchar(255) NOT NULL,
  `receipt_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `amount`, `currency`, `description`, `stripe_charge_id`, `receipt_email`, `created_at`, `user_id`, `name`) VALUES
(87, 200000, 'pkr', 'Payment from user (user@gmail.com)', 'ch_3PymgS2LBNlbJVWr0dKYaQrx', 'user@gmail.com', '2024-09-14 03:29:56', 41, 'user'),
(89, 200000, 'pkr', 'Payment from user (user@gmail.com)', 'ch_3Pymm02LBNlbJVWr0Y7hyjl2', 'user@gmail.com', '2024-09-14 03:35:40', 41, 'user'),
(90, 455500, 'pkr', 'Payment from Ali (jawwadkhan0931@gmail.com)', 'ch_3Pymnc2LBNlbJVWr0T5RMAST', 'jawwadkhan0931@gmail.com', '2024-09-14 03:37:21', 41, 'Ali'),
(91, 480000, 'pkr', 'Payment from jawwad (jawwadkhan0931@gmail.com)', 'ch_3PyzUK2LBNlbJVWr0NKH3B7I', 'jawwadkhan0931@gmail.com', '2024-09-14 17:10:24', 47, 'jawwad'),
(92, 480000, 'pkr', 'Payment from jawwad (jawwadkhan0931@gmail.com)', 'ch_3PyzsN2LBNlbJVWr1kjsDb6D', 'jawwadkhan0931@gmail.com', '2024-09-14 17:35:18', 47, 'jawwad');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `employee_id`, `rating`, `comment`, `created_at`, `user_id`) VALUES
(22, 55, 4.00, 'I rate 4 because he man hard worker and very experienced i suggest everyone to hire this man', '2024-09-14 03:44:30', 41),
(23, 57, 3.00, 'He is such a nice person ', '2024-09-14 16:57:05', 47),
(24, 61, 0.00, 'hard worker man', '2024-09-14 17:04:44', 47);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sales_percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `image`, `sales_percentage`, `created_at`) VALUES
(14, 'uploads/plumber banner.jpg', 56.00, '2024-09-14 03:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `profileimage` varchar(255) DEFAULT NULL,
  `AcceptTerm` tinyint(1) NOT NULL DEFAULT 0,
  `role` enum('user','employee','admin') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `age`, `contact`, `profileimage`, `AcceptTerm`, `role`, `created_at`, `expires_at`, `access_token`) VALUES
(40, 'admin', 'admin@gmail.com', '$2y$10$U4mvJ7hcBqU3pwOfEGKlw.RMw02uUcCxXS/SZzDt2GYGUEoozTyFS', 27, '0344903456', 'uploads/admin.jpg', 1, 'admin', '2024-09-13 17:42:37', NULL, NULL),
(41, 'user', 'user@gmail.com', '$2y$10$..WgM4tYY.0shy1YCgwGVe5wp0u.JzPOnoHz6GwqTm0r.p4fr2U7m', 45, '0350002001', 'uploads/user.jpg', 1, 'user', '2024-09-13 17:43:42', NULL, NULL),
(42, 'employee', 'employee@gmail.com', '$2y$10$1vqTvJn8v5ovhIEE/zMFtOpP3xirZuNnnlCn70PnvUkE.rsOnjvlu', 15, '0356982145', 'uploads/emp.jpg', 1, 'employee', '2024-09-13 17:45:33', NULL, NULL),
(43, 'Ali', 'Ali@gmail.com', '$2y$10$l51MgiOM4cpXwd7Fg8YizOxF1WI9vNgHzb07gbTML8UBByIAQdGyu', 24, '0350993221', 'uploads/avartar8.jpg', 1, 'employee', '2024-09-14 09:19:42', NULL, NULL),
(44, 'Jameel', 'jameel@gmail.com', '$2y$10$w8MeS6L1dPopPC39CTREkO1q.6zGoIS1OYaKvyyzw4oH60ntPg6B.', 23, '035276521', 'uploads/avatar7.jpg', 1, 'user', '2024-09-14 09:20:40', NULL, NULL),
(45, 'khalid', 'khalid@gmail.com', '$2y$10$9KEGqX3.NF66.k3aJyCWe.qKkCBDa6FNpM9f0vrcbFSQImCOr6yrS', 43, '0345768211', 'uploads/avatar6.jpg', 1, 'employee', '2024-09-14 09:21:23', NULL, NULL),
(46, 'Malik', 'Malik@gmail.com', '$2y$10$/SHNtVzHdXJJSrnUZhguWe8ei0Gap7FmgMTmimk5L04Y58OeQoAf.', 27, '0357684212', 'uploads/avartar5.jpg', 1, 'employee', '2024-09-14 09:22:25', NULL, NULL),
(47, 'jawwad', 'jawwadkhan0931@gmail.com', '$2y$10$fMcwG/2cfwDJXrvpgClfs.dCQCOhHqIKCW7FizMaOH9Mx.zLwVXVy', 34, '03400028804', 'uploads/avatar4.jpg', 1, 'user', '2024-09-14 09:23:19', NULL, NULL),
(48, 'sabir', 'sabir@gmail.com', '$2y$10$cfS8LSVk2qH3MEVszyPmOeHeDcaTYXwllsOaotgE9XoRfEVle9St2', 18, '034589210', 'uploads/avartar3.jpg', 1, 'employee', '2024-09-14 09:24:13', NULL, NULL),
(49, 'zain', 'zain@gmail.com', '$2y$10$dwShhZS9Wd0q5XUxmaEpa.aqR9mWTJ9G8sZCkW63YwXU8RxAe20/y', 36, '0357892311', 'uploads/avatar2.jpg', 1, 'user', '2024-09-14 09:24:58', NULL, NULL),
(50, 'jkhan', 'kj768978@gmail.com', '$2y$10$DJhYaoqE3FyEDcn5fQE7zOUNqttUzJhOhWt.lGZxUZJkuWlCkKmH2', 26, '0387342100', 'uploads/avatar1.jpg', 1, 'employee', '2024-09-14 09:26:48', NULL, NULL),
(51, 'jawwad638', 'jawwadk638@gmail.com', '$2y$10$2omTWIiZcUtFMEMyAux/hOL8gUSyoBW155Jiy8fA8K5ShliPoFctW', 18, '0355168721', 'uploads/avatar9.jpg', 1, 'admin', '2024-09-14 09:31:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_discounts`
--

CREATE TABLE `user_discounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_discounts`
--

INSERT INTO `user_discounts` (`id`, `user_id`, `discount_id`) VALUES
(1, 41, 15),
(2, 41, 16),
(3, 41, 1),
(4, 47, 17),
(5, 47, 18);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee_user` (`user_id`);

--
-- Indexes for table `employee_work`
--
ALTER TABLE `employee_work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `googleapikey`
--
ALTER TABLE `googleapikey`
  ADD PRIMARY KEY (`google_id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `otp_messages`
--
ALTER TABLE `otp_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_discounts`
--
ALTER TABLE `user_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `discount_id` (`discount_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `counter`
--
ALTER TABLE `counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `employee_work`
--
ALTER TABLE `employee_work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `googleapikey`
--
ALTER TABLE `googleapikey`
  MODIFY `google_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `otp_messages`
--
ALTER TABLE `otp_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_discounts`
--
ALTER TABLE `user_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `counter`
--
ALTER TABLE `counter`
  ADD CONSTRAINT `counter_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `counter_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employee_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `employee_work`
--
ALTER TABLE `employee_work`
  ADD CONSTRAINT `employee_work_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  ADD CONSTRAINT `employee_work_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `employee_work_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `otp_messages`
--
ALTER TABLE `otp_messages`
  ADD CONSTRAINT `otp_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_discounts`
--
ALTER TABLE `user_discounts`
  ADD CONSTRAINT `user_discounts_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
