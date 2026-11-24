-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 24, 2026 at 10:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehicle_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_expiry` date NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `phone`, `email`, `license_number`, `license_expiry`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Abdul Karim', '01711000001', 'karim@example.com', 'DL-1001', '2028-03-15', 'Mirpur, Dhaka', 1, '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(2, 'Rafiqul Islam', '01711000002', 'rafiq@example.com', 'DL-1002', '2027-08-20', 'Uttara, Dhaka', 1, '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(3, 'Jamal Uddin', '01711000003', NULL, 'DL-1003', '2026-12-10', 'Gazipur', 1, '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(4, 'Sohel Rana', '01711000004', 'sohel@example.com', 'DL-1004', '2029-01-05', 'Savar, Dhaka', 1, '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(5, 'Mizanur Rahman', '01711000005', NULL, 'DL-1005', '2026-05-30', 'Narayanganj', 0, '2026-11-22 05:29:51', '2026-11-22 05:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_logs`
--

CREATE TABLE `fuel_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `fuel_date` date NOT NULL,
  `liters` decimal(8,2) NOT NULL,
  `price_per_liter` decimal(8,2) NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `odometer` int UNSIGNED DEFAULT NULL,
  `station` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_logs`
--

INSERT INTO `fuel_logs` (`id`, `vehicle_id`, `driver_id`, `fuel_date`, `liters`, `price_per_liter`, `total_cost`, `odometer`, `station`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-10-02', 35.00, 125.00, 4375.00, 47800, 'Padma Oil Mirpur', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(2, 1, 1, '2026-10-20', 32.50, 125.00, 4062.50, 48200, 'Padma Oil Mirpur', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(3, 2, 2, '2026-10-05', 60.00, 109.00, 6540.00, 91600, 'Meghna Petrol Pump', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(4, 4, 4, '2026-10-15', 48.00, 109.00, 5232.00, 60700, 'Jamuna Oil Savar', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(5, 6, NULL, '2026-10-25', 8.00, 125.00, 1000.00, 15400, 'Padma Oil Uttara', '2026-11-22 05:29:51', '2026-11-22 05:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `maintenances`
--

CREATE TABLE `maintenances` (
  `id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `type` enum('oil_change','tire','brake','battery','general_service','repair','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general_service',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_date` date NOT NULL,
  `next_service_date` date DEFAULT NULL,
  `odometer` int UNSIGNED DEFAULT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `workshop` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenances`
--

INSERT INTO `maintenances` (`id`, `vehicle_id`, `type`, `description`, `service_date`, `next_service_date`, `odometer`, `cost`, `workshop`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'oil_change', 'Engine oil + filter', '2026-08-12', '2027-02-12', 46500, 6500.00, 'AutoCare Mirpur', 'completed', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(2, 2, 'brake', 'Front brake pads replaced', '2026-09-03', '2027-03-03', 90500, 18500.00, 'Prime Motors', 'completed', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(3, 3, 'repair', 'Gearbox repair', '2026-10-28', NULL, 134000, 85000.00, 'Nitol Workshop', 'in_progress', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(4, 4, 'tire', '4 new tires', '2026-07-20', '2028-07-20', 59800, 52000.00, 'Tire Zone', 'completed', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(5, 1, 'general_service', 'Annual service', '2026-11-20', '2027-11-20', NULL, 12000.00, 'AutoCare Mirpur', 'scheduled', '2026-11-22 05:29:51', '2026-11-22 05:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `parking_records`
--

CREATE TABLE `parking_records` (
  `id` bigint UNSIGNED NOT NULL,
  `parking_slot_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `parked_at` datetime NOT NULL,
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parking_records`
--

INSERT INTO `parking_records` (`id`, `parking_slot_id`, `vehicle_id`, `parked_at`, `released_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-11-22 11:29:52', NULL, '2026-11-22 05:29:52', '2026-11-22 05:29:52'),
(2, 2, 2, '2026-11-22 11:29:52', NULL, '2026-11-22 05:29:52', '2026-11-22 05:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `parking_slots`
--

CREATE TABLE `parking_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `floor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ground Floor',
  `zone` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` smallint UNSIGNED NOT NULL,
  `code` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parking_slots`
--

INSERT INTO `parking_slots` (`id`, `floor`, `zone`, `number`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ground Floor', 'A', 1, 'A1', 1, '2026-11-22 05:29:52', '2026-11-22 05:29:52'),
(2, 'Ground Floor', 'A', 2, 'A2', 1, '2026-11-22 05:29:52', '2026-11-22 05:29:52'),
(3, 'Ground Floor', 'A', 3, 'A3', 1, '2026-11-22 05:29:52', '2026-11-22 05:29:52'),
(4, 'Ground Floor', 'B', 1, 'B1', 1, '2026-11-22 05:29:52', '2026-11-22 05:29:52'),
(5, 'Ground Floor', 'B', 2, 'B2', 1, '2026-11-22 05:29:52', '2026-11-22 05:29:52'),
(16, 'Ground Floor', 'B', 3, 'B3', 1, '2026-11-22 00:03:23', '2026-11-22 00:03:23'),
(17, 'Ground Floor', 'C', 1, 'C1', 1, '2026-11-22 00:03:29', '2026-11-22 00:03:29'),
(18, 'Ground Floor', 'C', 2, 'C2', 1, '2026-11-22 00:03:29', '2026-11-22 00:03:29'),
(19, 'Ground Floor', 'C', 3, 'C3', 1, '2026-11-22 00:03:29', '2026-11-22 00:03:29'),
(20, 'Ground Floor', 'D', 1, 'D1', 1, '2026-11-22 00:03:35', '2026-11-22 00:03:35'),
(21, 'Ground Floor', 'D', 2, 'D2', 1, '2026-11-22 00:03:35', '2026-11-22 00:03:35'),
(22, 'Ground Floor', 'D', 3, 'D3', 1, '2026-11-22 00:03:35', '2026-11-22 00:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(220014112, 'Md. Julfikar Ali Saju', 'esaju.cse@gmail.com', '2026-11-01 10:49:53', '$2y$10$XCnYDeUPTi560gtv4IPU.OvnnTrTk/cd4XTH0vwlodQ2NNtcLnSSO', 'admin', 1, NULL, '2026-11-01 10:49:53', '2026-11-22 05:53:11'),
(220014113, 'Admin', 'admin@example.com', NULL, '$2y$10$LWHmwZbhIfyRzWAXtk8/d.4.UXayr3z0kF7wIFykrhZtyAe5uKROu', 'admin', 1, NULL, '2026-11-01 10:56:20', '2026-11-01 22:18:56'),
(220014114, 'Md. Julfikar Ali Saju', 'sae7.ict@pgcb.gov.bd', NULL, '$2y$10$8WDitAsUQ4uUFFBBaelI3uvhhoirRL/ikJrKpW4sCowNsFLlzdYyK', 'staff', 1, NULL, '2026-11-01 21:33:51', '2026-11-01 21:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL,
  `vehicle_type_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `registration_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` smallint UNSIGNED NOT NULL,
  `color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chassis_no` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_no` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fuel_type` enum('petrol','diesel','octane','cng','lpg','electric','hybrid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'petrol',
  `seat_capacity` tinyint UNSIGNED NOT NULL DEFAULT '4',
  `odometer` int UNSIGNED NOT NULL DEFAULT '0',
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(12,2) DEFAULT NULL,
  `insurance_expiry` date DEFAULT NULL,
  `tax_token_expiry` date DEFAULT NULL,
  `fitness_expiry` date DEFAULT NULL,
  `status` enum('active','in_maintenance','inactive','sold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_type_id`, `driver_id`, `registration_no`, `brand`, `model`, `year`, `color`, `chassis_no`, `engine_no`, `fuel_type`, `seat_capacity`, `odometer`, `purchase_date`, `purchase_price`, `insurance_expiry`, `tax_token_expiry`, `fitness_expiry`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'DHAKA METRO-GA-11-2345', 'Toyota', 'Corolla', 2020, 'RED', 'CH-TY-0001', 'EN-TY-0001', 'octane', 5, 71000, '2020-06-10', 3200000.00, '2027-01-31', '2027-03-31', '2027-06-30', 'active', NULL, '2026-11-22 05:29:51', '2026-11-24 01:38:54'),
(2, 2, 2, 'DHAKA METRO-HA-22-7788', 'Toyota', 'Land Cruiser', 2019, 'Black', 'CH-TY-0002', 'EN-TY-0002', 'diesel', 7, 92100, '2019-09-01', 9800000.00, '2026-12-15', '2026-11-20', '2027-02-28', 'active', 'Executive vehicle', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(3, 3, 3, 'DHAKA METRO-CHA-13-4521', 'Toyota', 'HiAce', 2018, 'Silver', 'CH-TY-0003', 'EN-TY-0003', 'octane', 12, 134500, '2018-04-22', 4200000.00, '2026-11-25', '2027-04-30', '2026-12-31', 'in_maintenance', NULL, '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(4, 4, 4, 'DHAKA METRO-THA-11-9090', 'Mitsubishi', 'L200', 2021, 'Blue', 'CH-MT-0004', 'EN-MT-0004', 'diesel', 5, 61000, '2021-02-14', 3600000.00, '2027-05-31', '2027-05-31', '2027-08-31', 'active', NULL, '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(5, 5, NULL, 'DHAKA METRO-DA-11-1122', 'Tata', 'LPT 1613', 2017, 'Red', 'CH-TT-0005', 'EN-TT-0005', 'diesel', 3, 210800, '2017-11-05', 5200000.00, '2026-10-15', '2026-09-30', '2026-10-31', 'inactive', 'Fitness certificate expired', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(6, 6, NULL, 'DHAKA METRO-LA-33-5566', 'Honda', 'CB Hornet', 2022, 'Black', 'CH-HN-0006', 'EN-HN-0006', 'octane', 2, 15400, '2022-07-19', 310000.00, '2027-07-31', '2027-07-31', '2027-07-31', 'active', NULL, '2026-11-22 05:29:51', '2026-11-22 05:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sedan', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(2, 'SUV', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(3, 'Microbus', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(4, 'Pickup', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(5, 'Truck', '2026-11-22 05:29:51', '2026-11-22 05:29:51'),
(6, 'Motorcycle', '2026-11-22 05:29:51', '2026-11-22 05:29:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_license_number_unique` (`license_number`);

--
-- Indexes for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuel_logs_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `fuel_logs_driver_id_foreign` (`driver_id`),
  ADD KEY `fuel_logs_fuel_date_index` (`fuel_date`);

--
-- Indexes for table `maintenances`
--
ALTER TABLE `maintenances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenances_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `maintenances_service_date_index` (`service_date`);

--
-- Indexes for table `parking_records`
--
ALTER TABLE `parking_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parking_records_parking_slot_id_released_at_index` (`parking_slot_id`,`released_at`),
  ADD KEY `parking_records_vehicle_id_released_at_index` (`vehicle_id`,`released_at`);

--
-- Indexes for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parking_slots_floor_code_unique` (`floor`,`code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_registration_no_unique` (`registration_no`),
  ADD KEY `vehicles_vehicle_type_id_foreign` (`vehicle_type_id`),
  ADD KEY `vehicles_driver_id_foreign` (`driver_id`),
  ADD KEY `vehicles_status_index` (`status`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_types_name_unique` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `maintenances`
--
ALTER TABLE `maintenances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `parking_records`
--
ALTER TABLE `parking_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parking_slots`
--
ALTER TABLE `parking_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220014115;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD CONSTRAINT `fuel_logs_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fuel_logs_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenances`
--
ALTER TABLE `maintenances`
  ADD CONSTRAINT `maintenances_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parking_records`
--
ALTER TABLE `parking_records`
  ADD CONSTRAINT `parking_records_parking_slot_id_foreign` FOREIGN KEY (`parking_slot_id`) REFERENCES `parking_slots` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `parking_records_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
