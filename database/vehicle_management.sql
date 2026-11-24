-- ============================================================
-- Vehicle Management System - MySQL / MariaDB schema + sample data
-- Laravel 11/12/13 compatible (utf8mb4, InnoDB)
-- Import:  mysql -u root -p vehicle_management < vehicle_management.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS `vehicle_management`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vehicle_management`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `parking_records`;
DROP TABLE IF EXISTS `parking_slots`;
DROP TABLE IF EXISTS `fuel_logs`;
DROP TABLE IF EXISTS `maintenances`;
DROP TABLE IF EXISTS `vehicles`;
DROP TABLE IF EXISTS `drivers`;
DROP TABLE IF EXISTS `vehicle_types`;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- vehicle_types
-- ------------------------------------------------------------
CREATE TABLE `vehicle_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- drivers
-- ------------------------------------------------------------
CREATE TABLE `drivers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) NULL DEFAULT NULL,
  `license_number` VARCHAR(60) NOT NULL,
  `license_expiry` DATE NOT NULL,
  `address` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drivers_license_number_unique` (`license_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- vehicles
-- ------------------------------------------------------------
CREATE TABLE `vehicles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_type_id` BIGINT UNSIGNED NOT NULL,
  `driver_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `registration_no` VARCHAR(50) NOT NULL,
  `brand` VARCHAR(80) NOT NULL,
  `model` VARCHAR(80) NOT NULL,
  `year` SMALLINT UNSIGNED NOT NULL,
  `color` VARCHAR(40) NULL DEFAULT NULL,
  `chassis_no` VARCHAR(80) NULL DEFAULT NULL,
  `engine_no` VARCHAR(80) NULL DEFAULT NULL,
  `fuel_type` ENUM('petrol','diesel','octane','cng','lpg','electric','hybrid') NOT NULL DEFAULT 'petrol',
  `seat_capacity` TINYINT UNSIGNED NOT NULL DEFAULT 4,
  `odometer` INT UNSIGNED NOT NULL DEFAULT 0,
  `purchase_date` DATE NULL DEFAULT NULL,
  `purchase_price` DECIMAL(12,2) NULL DEFAULT NULL,
  `insurance_expiry` DATE NULL DEFAULT NULL,
  `tax_token_expiry` DATE NULL DEFAULT NULL,
  `fitness_expiry` DATE NULL DEFAULT NULL,
  `status` ENUM('active','in_maintenance','inactive','sold') NOT NULL DEFAULT 'active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_registration_no_unique` (`registration_no`),
  KEY `vehicles_vehicle_type_id_foreign` (`vehicle_type_id`),
  KEY `vehicles_driver_id_foreign` (`driver_id`),
  KEY `vehicles_status_index` (`status`),
  CONSTRAINT `vehicles_vehicle_type_id_foreign` FOREIGN KEY (`vehicle_type_id`)
    REFERENCES `vehicle_types` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `vehicles_driver_id_foreign` FOREIGN KEY (`driver_id`)
    REFERENCES `drivers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- maintenances
-- ------------------------------------------------------------
CREATE TABLE `maintenances` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('oil_change','tire','brake','battery','general_service','repair','other') NOT NULL DEFAULT 'general_service',
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `service_date` DATE NOT NULL,
  `next_service_date` DATE NULL DEFAULT NULL,
  `odometer` INT UNSIGNED NULL DEFAULT NULL,
  `cost` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `workshop` VARCHAR(150) NULL DEFAULT NULL,
  `status` ENUM('scheduled','in_progress','completed') NOT NULL DEFAULT 'completed',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenances_vehicle_id_foreign` (`vehicle_id`),
  KEY `maintenances_service_date_index` (`service_date`),
  CONSTRAINT `maintenances_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`)
    REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- fuel_logs
-- ------------------------------------------------------------
CREATE TABLE `fuel_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` BIGINT UNSIGNED NOT NULL,
  `driver_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `fuel_date` DATE NOT NULL,
  `liters` DECIMAL(8,2) NOT NULL,
  `price_per_liter` DECIMAL(8,2) NOT NULL,
  `total_cost` DECIMAL(12,2) NOT NULL,
  `odometer` INT UNSIGNED NULL DEFAULT NULL,
  `station` VARCHAR(150) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fuel_logs_vehicle_id_foreign` (`vehicle_id`),
  KEY `fuel_logs_driver_id_foreign` (`driver_id`),
  KEY `fuel_logs_fuel_date_index` (`fuel_date`),
  CONSTRAINT `fuel_logs_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`)
    REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fuel_logs_driver_id_foreign` FOREIGN KEY (`driver_id`)
    REFERENCES `drivers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SAMPLE DATA
-- ============================================================

INSERT INTO `vehicle_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Sedan',   NOW(), NOW()),
(2, 'SUV',     NOW(), NOW()),
(3, 'Microbus',NOW(), NOW()),
(4, 'Pickup',  NOW(), NOW()),
(5, 'Truck',   NOW(), NOW()),
(6, 'Motorcycle', NOW(), NOW());

INSERT INTO `drivers` (`id`, `name`, `phone`, `email`, `license_number`, `license_expiry`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Abdul Karim',   '01711000001', 'karim@example.com',  'DL-1001', '2028-03-15', 'Mirpur, Dhaka',     1, NOW(), NOW()),
(2, 'Rafiqul Islam', '01711000002', 'rafiq@example.com',  'DL-1002', '2027-08-20', 'Uttara, Dhaka',     1, NOW(), NOW()),
(3, 'Jamal Uddin',   '01711000003', NULL,                 'DL-1003', '2026-12-10', 'Gazipur',           1, NOW(), NOW()),
(4, 'Sohel Rana',    '01711000004', 'sohel@example.com',  'DL-1004', '2029-01-05', 'Savar, Dhaka',      1, NOW(), NOW()),
(5, 'Mizanur Rahman','01711000005', NULL,                 'DL-1005', '2026-05-30', 'Narayanganj',       0, NOW(), NOW());

INSERT INTO `vehicles`
(`id`,`vehicle_type_id`,`driver_id`,`registration_no`,`brand`,`model`,`year`,`color`,`chassis_no`,`engine_no`,`fuel_type`,`seat_capacity`,`odometer`,`purchase_date`,`purchase_price`,`insurance_expiry`,`tax_token_expiry`,`fitness_expiry`,`status`,`notes`,`created_at`,`updated_at`) VALUES
(1,1,1,'DHAKA METRO-GA-11-2345','Toyota','Corolla',2020,'White','CH-TY-0001','EN-TY-0001','octane',5,48200,'2020-06-10',3200000.00,'2027-01-31','2027-03-31','2027-06-30','active',NULL,NOW(),NOW()),
(2,2,2,'DHAKA METRO-HA-22-7788','Toyota','Land Cruiser',2019,'Black','CH-TY-0002','EN-TY-0002','diesel',7,92100,'2019-09-01',9800000.00,'2026-12-15','2026-11-20','2027-02-28','active','Executive vehicle',NOW(),NOW()),
(3,3,3,'DHAKA METRO-CHA-13-4521','Toyota','HiAce',2018,'Silver','CH-TY-0003','EN-TY-0003','octane',12,134500,'2018-04-22',4200000.00,'2026-11-25','2027-04-30','2026-12-31','in_maintenance',NULL,NOW(),NOW()),
(4,4,4,'DHAKA METRO-THA-11-9090','Mitsubishi','L200',2021,'Blue','CH-MT-0004','EN-MT-0004','diesel',5,61000,'2021-02-14',3600000.00,'2027-05-31','2027-05-31','2027-08-31','active',NULL,NOW(),NOW()),
(5,5,NULL,'DHAKA METRO-DA-11-1122','Tata','LPT 1613',2017,'Red','CH-TT-0005','EN-TT-0005','diesel',3,210800,'2017-11-05',5200000.00,'2026-10-15','2026-09-30','2026-10-31','inactive','Fitness certificate expired',NOW(),NOW()),
(6,6,NULL,'DHAKA METRO-LA-33-5566','Honda','CB Hornet',2022,'Black','CH-HN-0006','EN-HN-0006','octane',2,15400,'2022-07-19',310000.00,'2027-07-31','2027-07-31','2027-07-31','active',NULL,NOW(),NOW());

INSERT INTO `maintenances`
(`id`,`vehicle_id`,`type`,`description`,`service_date`,`next_service_date`,`odometer`,`cost`,`workshop`,`status`,`created_at`,`updated_at`) VALUES
(1,1,'oil_change','Engine oil + filter','2026-08-12','2027-02-12',46500,6500.00,'AutoCare Mirpur','completed',NOW(),NOW()),
(2,2,'brake','Front brake pads replaced','2026-09-03','2027-03-03',90500,18500.00,'Prime Motors','completed',NOW(),NOW()),
(3,3,'repair','Gearbox repair','2026-10-28',NULL,134000,85000.00,'Nitol Workshop','in_progress',NOW(),NOW()),
(4,4,'tire','4 new tires','2026-07-20','2028-07-20',59800,52000.00,'Tire Zone','completed',NOW(),NOW()),
(5,1,'general_service','Annual service','2026-11-20','2027-11-20',NULL,12000.00,'AutoCare Mirpur','scheduled',NOW(),NOW());

INSERT INTO `fuel_logs`
(`id`,`vehicle_id`,`driver_id`,`fuel_date`,`liters`,`price_per_liter`,`total_cost`,`odometer`,`station`,`created_at`,`updated_at`) VALUES
(1,1,1,'2026-10-02',35.00,125.00,4375.00,47800,'Padma Oil Mirpur',NOW(),NOW()),
(2,1,1,'2026-10-20',32.50,125.00,4062.50,48200,'Padma Oil Mirpur',NOW(),NOW()),
(3,2,2,'2026-10-05',60.00,109.00,6540.00,91600,'Meghna Petrol Pump',NOW(),NOW()),
(4,4,4,'2026-10-15',48.00,109.00,5232.00,60700,'Jamuna Oil Savar',NOW(),NOW()),
(5,6,NULL,'2026-10-25',8.00,125.00,1000.00,15400,'Padma Oil Uttara',NOW(),NOW());

-- ============================================================
-- AUTH TABLES
-- ============================================================
-- ============================================================
-- AUTH TABLES for the Vehicle Management System
-- Run ONCE on your existing `vehicle_management` database
-- (phpMyAdmin: select the database -> Import / SQL tab).
-- Your existing tables and data are NOT touched.
-- ============================================================

-- ------------------------------------------------------------
-- users  (role: admin = everything + manage users, staff = everything else)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If you ALREADY have Laravel's default `users` table (the CREATE above is then skipped),
-- add the two extra columns by running these two statements (only if the columns are missing):
--
--   ALTER TABLE `users` ADD COLUMN `role` enum('admin','staff') NOT NULL DEFAULT 'staff' AFTER `password`;
--   ALTER TABLE `users` ADD COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 AFTER `role`;
--   UPDATE `users` SET `role` = 'admin' ORDER BY `id` LIMIT 1;   -- make the first user an admin

-- ------------------------------------------------------------
-- sessions / cache: only used when .env has SESSION_DRIVER=database / CACHE_STORE=database
-- (the Laravel 11-13 defaults). Harmless if you use the file drivers instead.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- No shared default administrator is inserted. Set ADMIN_EMAIL and a unique,
-- 12+ character ADMIN_PASSWORD in .env, then run `php artisan db:seed`.

-- ============================================================
-- PARKING TABLES
-- ============================================================
-- ============================================================
-- PARKING TABLES for the Vehicle Management System
-- Run ONCE on your existing `vehicle_management` database
-- (phpMyAdmin: select the database -> Import / SQL tab).
-- Your existing tables and data are NOT touched.
-- Needs the `vehicles` table to exist already.
-- ============================================================

-- ------------------------------------------------------------
-- parking_slots: one row per bay. Floor "Ground Floor" + row "A" + number 1 => code "A1"
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `parking_slots` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `floor` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ground Floor',
  `zone` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` smallint UNSIGNED NOT NULL,
  `code` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sensor_occupied` tinyint(1) DEFAULT NULL,
  `sensor_updated_at` datetime DEFAULT NULL,
  `last_detected_plate` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_plate_detected_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parking_slots_floor_code_unique` (`floor`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- parking_records: one row per parking stay. released_at IS NULL = parked right now.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `parking_records` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parking_slot_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED DEFAULT NULL,
  `detected_plate` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `parked_at` datetime NOT NULL,
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parking_records_parking_slot_id_released_at_index` (`parking_slot_id`,`released_at`),
  KEY `parking_records_vehicle_id_released_at_index` (`vehicle_id`,`released_at`),
  CONSTRAINT `parking_records_parking_slot_id_foreign` FOREIGN KEY (`parking_slot_id`) REFERENCES `parking_slots` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `parking_records_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Ground floor: row A = A1, A2, A3   row B = B1, B2
-- (INSERT IGNORE: running this file twice does not create duplicates)
-- ------------------------------------------------------------
INSERT IGNORE INTO `parking_slots` (`floor`, `zone`, `number`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
('Ground Floor', 'A', 1, 'A1', 1, NOW(), NOW()),
('Ground Floor', 'A', 2, 'A2', 1, NOW(), NOW()),
('Ground Floor', 'A', 3, 'A3', 1, NOW(), NOW()),
('Ground Floor', 'B', 1, 'B1', 1, NOW(), NOW()),
('Ground Floor', 'B', 2, 'B2', 1, NOW(), NOW());

-- ------------------------------------------------------------
-- Sample: vehicle 1 is parked in A1 and vehicle 2 in A2 (only if there are no parking records yet).
-- Delete this statement if you do not want sample data.
-- ------------------------------------------------------------
INSERT INTO `parking_records` (`parking_slot_id`, `vehicle_id`, `parked_at`, `released_at`, `created_at`, `updated_at`)
SELECT s.`id`, v.`id`, NOW(), NULL, NOW(), NOW()
FROM `parking_slots` s
JOIN `vehicles` v ON (s.`code` = 'A1' AND v.`id` = 1) OR (s.`code` = 'A2' AND v.`id` = 2)
WHERE s.`floor` = 'Ground Floor'
  AND NOT EXISTS (SELECT 1 FROM `parking_records`);
