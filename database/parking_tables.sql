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
-- vehicle_id is nullable to represent sensor-detected vehicles not yet identified.
-- Re-importing this file does not alter an existing table; run the Laravel migration
-- 2026_11_22_000001_add_parking_sensor_integration on an existing installation.
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
