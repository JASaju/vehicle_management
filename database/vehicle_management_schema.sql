-- Vehicle Management System business-schema reference.
-- MySQL 8+ syntax, derived from database/migrations.
-- DDL only: no INSERTs, sample data, secrets, or DROP statements.
-- Use `php artisan migrate` as the authoritative installation/upgrade procedure.

CREATE TABLE `vehicle_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `drivers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `license_number` VARCHAR(60) NOT NULL,
  `license_expiry` DATE NOT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drivers_license_number_unique` (`license_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `vehicles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_type_id` BIGINT UNSIGNED NOT NULL,
  `driver_id` BIGINT UNSIGNED DEFAULT NULL,
  `registration_no` VARCHAR(50) NOT NULL,
  `brand` VARCHAR(80) NOT NULL,
  `model` VARCHAR(80) NOT NULL,
  `year` SMALLINT UNSIGNED NOT NULL,
  `color` VARCHAR(40) DEFAULT NULL,
  `chassis_no` VARCHAR(80) DEFAULT NULL,
  `engine_no` VARCHAR(80) DEFAULT NULL,
  `fuel_type` ENUM('petrol', 'diesel', 'octane', 'cng', 'lpg', 'electric', 'hybrid') NOT NULL DEFAULT 'petrol',
  `seat_capacity` TINYINT UNSIGNED NOT NULL DEFAULT 4,
  `odometer` INT UNSIGNED NOT NULL DEFAULT 0,
  `purchase_date` DATE DEFAULT NULL,
  `purchase_price` DECIMAL(12,2) DEFAULT NULL,
  `insurance_expiry` DATE DEFAULT NULL,
  `tax_token_expiry` DATE DEFAULT NULL,
  `fitness_expiry` DATE DEFAULT NULL,
  `status` ENUM('active', 'in_maintenance', 'inactive', 'sold') NOT NULL DEFAULT 'active',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_registration_no_unique` (`registration_no`),
  KEY `vehicles_vehicle_type_id_foreign` (`vehicle_type_id`),
  KEY `vehicles_driver_id_foreign` (`driver_id`),
  KEY `vehicles_status_index` (`status`),
  CONSTRAINT `vehicles_vehicle_type_id_foreign_key`
    FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `vehicles_driver_id_foreign_key`
    FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `maintenances` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('oil_change', 'tire', 'brake', 'battery', 'general_service', 'repair', 'other') NOT NULL DEFAULT 'general_service',
  `description` VARCHAR(255) DEFAULT NULL,
  `service_date` DATE NOT NULL,
  `next_service_date` DATE DEFAULT NULL,
  `odometer` INT UNSIGNED DEFAULT NULL,
  `cost` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `workshop` VARCHAR(150) DEFAULT NULL,
  `status` ENUM('scheduled', 'in_progress', 'completed') NOT NULL DEFAULT 'completed',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenances_vehicle_id_foreign` (`vehicle_id`),
  KEY `maintenances_service_date_index` (`service_date`),
  CONSTRAINT `maintenances_vehicle_id_foreign_key`
    FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fuel_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` BIGINT UNSIGNED NOT NULL,
  `driver_id` BIGINT UNSIGNED DEFAULT NULL,
  `fuel_date` DATE NOT NULL,
  `liters` DECIMAL(8,2) NOT NULL,
  `price_per_liter` DECIMAL(8,2) NOT NULL,
  `total_cost` DECIMAL(12,2) NOT NULL,
  `odometer` INT UNSIGNED DEFAULT NULL,
  `station` VARCHAR(150) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fuel_logs_vehicle_id_foreign` (`vehicle_id`),
  KEY `fuel_logs_driver_id_foreign` (`driver_id`),
  KEY `fuel_logs_fuel_date_index` (`fuel_date`),
  CONSTRAINT `fuel_logs_vehicle_id_foreign_key`
    FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fuel_logs_driver_id_foreign_key`
    FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `parking_slots` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `floor` VARCHAR(50) NOT NULL DEFAULT 'Ground Floor',
  `zone` VARCHAR(5) NOT NULL,
  `number` SMALLINT UNSIGNED NOT NULL,
  `code` VARCHAR(12) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sensor_occupied` TINYINT(1) DEFAULT NULL,
  `sensor_updated_at` DATETIME DEFAULT NULL,
  `last_detected_plate` VARCHAR(50) DEFAULT NULL,
  `last_plate_detected_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parking_slots_floor_code_unique` (`floor`, `code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `parking_records` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parking_slot_id` BIGINT UNSIGNED NOT NULL,
  `vehicle_id` BIGINT UNSIGNED DEFAULT NULL,
  `parked_at` DATETIME NOT NULL,
  `released_at` DATETIME DEFAULT NULL,
  `detected_plate` VARCHAR(50) DEFAULT NULL,
  `source` VARCHAR(20) NOT NULL DEFAULT 'manual',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parking_records_parking_slot_id_released_at_index` (`parking_slot_id`, `released_at`),
  KEY `parking_records_vehicle_id_released_at_index` (`vehicle_id`, `released_at`),
  CONSTRAINT `parking_records_parking_slot_id_foreign_key`
    FOREIGN KEY (`parking_slot_id`) REFERENCES `parking_slots` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `parking_records_vehicle_id_foreign_key`
    FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
