-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2026 at 07:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `automatex`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `garage_id` bigint(20) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `invoice_amount` decimal(10,2) DEFAULT NULL,
  `invoice_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `vehicle_id`, `garage_id`, `booking_date`, `booking_time`, `service_type`, `notes`, `status`, `invoice_amount`, `invoice_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-03-14', '11:00:00', 'Engine Oil Change', NULL, 'confirmed', 50000.00, 'Oil', '2026-03-04 14:53:17', '2026-03-04 14:54:07'),
(2, 4, 2, '2026-03-30', '09:00:00', 'Engine Oil Change', NULL, 'pending', NULL, NULL, '2026-03-28 22:26:44', '2026-03-28 22:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@gmail.com|::1', 'i:1;', 1774754043),
('laravel-cache-admin@gmail.com|::1:timer', 'i:1774754043;', 1774754043),
('laravel-cache-m@gmail.com|::1', 'i:2;', 1774754356),
('laravel-cache-m@gmail.com|::1:timer', 'i:1774754356;', 1774754356);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_logs`
--

CREATE TABLE `fuel_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `liters` decimal(8,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `km_reading` int(11) NOT NULL,
  `km_per_liter` decimal(8,2) DEFAULT NULL,
  `fuel_station` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuel_logs`
--

INSERT INTO `fuel_logs` (`id`, `vehicle_id`, `date`, `liters`, `cost`, `km_reading`, `km_per_liter`, `fuel_station`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-04', 60.00, 18000.00, 36000, NULL, 'Lanka IOC, Maharagama', 'Full Tank', '2026-03-04 10:09:14', '2026-03-04 10:09:14'),
(2, 1, '2026-03-04', 55.00, 16500.00, 36500, 9.09, 'Lanka IOC, Maharagama', NULL, '2026-03-04 13:27:19', '2026-03-04 13:27:19'),
(3, 1, '2026-03-04', 60.00, 18000.01, 37000, 8.33, 'Lanka IOC, Maharagama', 'Full Tank', '2026-03-04 13:28:21', '2026-03-04 13:28:21'),
(4, 1, '2026-03-05', 60.00, 18500.00, 37750, 12.50, 'Lanka IOC, Maharagama', NULL, '2026-03-04 13:30:25', '2026-03-04 13:30:25'),
(6, 1, '2026-03-06', 65.00, 20000.00, 39000, 10.77, 'Lanka IOC, Maharagama', NULL, '2026-03-04 13:34:27', '2026-03-04 13:34:27'),
(7, 1, '2026-03-04', 70.00, 30000.00, 44100, 72.86, 'Lanka IOC, Maharagama', NULL, '2026-03-04 13:35:39', '2026-03-04 13:35:39'),
(8, 3, '2026-03-02', 50.00, 20000.00, 154000, NULL, 'Lanka IOC, Maharagama', NULL, '2026-03-07 03:18:40', '2026-03-07 03:18:40'),
(9, 5, '2026-03-29', 1.09, 2435.00, 25010, NULL, 'Lanka IOC, Maharagama', NULL, '2026-03-28 23:23:02', '2026-03-28 23:23:02');

-- --------------------------------------------------------

--
-- Table structure for table `garages`
--

CREATE TABLE `garages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `garages`
--

INSERT INTO `garages` (`id`, `user_id`, `name`, `address`, `city`, `phone`, `description`, `specialization`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'CatEye Detailers', '120/1,', 'Pannipitiya', '0754799994', 'We are here for u', 'Toyota Specialists', 1, '2026-03-04 14:52:15', '2026-03-04 14:52:15'),
(2, 4, 'Supun Service Center', '16,6 dgfgdg', 'Maharagama', '0713653487', NULL, NULL, 1, '2026-03-28 21:45:20', '2026-03-28 21:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedules`
--

CREATE TABLE `maintenance_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `interval_km` int(11) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'maintenance',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_schedules`
--

INSERT INTO `maintenance_schedules` (`id`, `service_name`, `interval_km`, `category`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Engine Oil & Filter Change', 5000, 'maintenance', 'Replace engine oil and oil filter to keep engine healthy.', NULL, NULL),
(2, 'Air Filter Replacement', 15000, 'maintenance', 'Replace air filter for better engine performance and fuel economy.', NULL, NULL),
(3, 'Tire Rotation & Balance', 10000, 'maintenance', 'Rotate and balance tires for even wear and smooth driving.', NULL, NULL),
(4, 'Brake Pad Inspection', 20000, 'inspection', 'Inspect brake pads for wear and replace if necessary.', NULL, NULL),
(5, 'Spark Plug Replacement', 30000, 'maintenance', 'Replace spark plugs for optimal engine combustion.', NULL, NULL),
(6, 'Transmission Fluid Change', 40000, 'maintenance', 'Change transmission fluid to protect gearbox.', NULL, NULL),
(7, 'Coolant Flush', 40000, 'maintenance', 'Flush and replace engine coolant to prevent overheating.', NULL, NULL),
(8, 'Timing Belt Replacement', 80000, 'maintenance', 'Replace timing belt to prevent engine damage.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_04_144202_add_role_to_users_table', 2),
(5, '2026_03_04_150033_create_vehicles_table', 3),
(6, '2026_03_04_152207_create_fuel_logs_table', 4),
(7, '2026_03_04_163813_create_service_logs_table', 5),
(8, '2026_03_04_183633_create_maintenance_schedules_table', 6),
(9, '2026_03_04_191716_add_qr_token_to_vehicles_table', 7),
(10, '2026_03_04_194645_create_parts_table', 8),
(11, '2026_03_04_200852_create_garages_table', 9),
(12, '2026_03_04_200853_create_bookings_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_make` varchar(255) NOT NULL,
  `vehicle_model` varchar(255) NOT NULL,
  `vehicle_year_from` int(11) NOT NULL,
  `vehicle_year_to` int(11) NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `part_category` varchar(255) NOT NULL,
  `oem_part_number` varchar(255) NOT NULL,
  `alternative_part_number` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `vehicle_make`, `vehicle_model`, `vehicle_year_from`, `vehicle_year_to`, `part_name`, `part_category`, `oem_part_number`, `alternative_part_number`, `brand`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Toyota', 'Vitz', 2014, 2020, 'Engine Oil Filter', 'Filters', '90915-YZZD2', 'DENSO 150-2004', 'Toyota Genuine', 'Engine oil filter for 1KR-FE engine. Replace every 5000km.', NULL, NULL),
(2, 'Toyota', 'Vitz', 2014, 2020, 'Air Filter', 'Filters', '17801-B1010', 'DENSO DCF137', 'Toyota Genuine', 'Air filter for 1KR-FE engine. Replace every 15000km.', NULL, NULL),
(3, 'Toyota', 'Vitz', 2014, 2020, 'Spark Plug', 'Spark Plugs', '90919-01253', 'NGK ILZKR7B-11S', 'Toyota Genuine', 'Iridium spark plug for 1KR-FE engine. Replace every 30000km.', NULL, NULL),
(4, 'Toyota', 'Vitz', 2014, 2020, 'Front Brake Pads', 'Brakes', '04465-52220', 'AKEBONO AN-696WK', 'Toyota Genuine', 'Front brake pad set. Inspect every 20000km.', NULL, NULL),
(5, 'Toyota', 'Vitz', 2014, 2020, 'Drive Belt', 'Belts', '90916-02647', 'GATES 6PK950', 'Toyota Genuine', 'Alternator drive belt. Replace every 80000km.', NULL, NULL),
(6, 'Toyota', 'Premio', 2007, 2021, 'Engine Oil Filter', 'Filters', '90915-03003', 'DENSO 150-2007', 'Toyota Genuine', 'Engine oil filter for 1ZZ-FE / 2ZR-FE engine. Replace every 5000km.', NULL, NULL),
(7, 'Toyota', 'Premio', 2007, 2021, 'Air Filter', 'Filters', '17801-22020', 'DENSO DCF056', 'Toyota Genuine', 'Air filter for 1ZZ-FE / 2ZR-FE engine. Replace every 15000km.', NULL, NULL),
(8, 'Toyota', 'Premio', 2007, 2021, 'Spark Plug', 'Spark Plugs', '90919-01210', 'NGK ILFR6B', 'Toyota Genuine', 'Iridium spark plug for 2ZR-FE engine. Replace every 30000km.', NULL, NULL),
(9, 'Toyota', 'Premio', 2007, 2021, 'Front Brake Pads', 'Brakes', '04465-02260', 'AKEBONO AN-697WK', 'Toyota Genuine', 'Front brake pad set. Inspect every 20000km.', NULL, NULL),
(10, 'Toyota', 'Premio', 2007, 2021, 'Cabin Air Filter', 'Filters', '87139-02090', 'DENSO DCF108', 'Toyota Genuine', 'Cabin/AC air filter. Replace every 15000km.', NULL, NULL),
(11, 'Suzuki', 'Alto', 2009, 2021, 'Engine Oil Filter', 'Filters', '16510-84M00', 'BOSCH P3337', 'Suzuki Genuine', 'Engine oil filter for K10B engine. Replace every 5000km.', NULL, NULL),
(12, 'Suzuki', 'Alto', 2009, 2021, 'Air Filter', 'Filters', '13780-68L00', 'BOSCH S0014', 'Suzuki Genuine', 'Air filter for K10B engine. Replace every 15000km.', NULL, NULL),
(13, 'Suzuki', 'Alto', 2009, 2021, 'Spark Plug', 'Spark Plugs', '09482-00523', 'NGK SILZKR7B11', 'Suzuki Genuine', 'Iridium spark plug for K10B engine. Replace every 30000km.', NULL, NULL),
(14, 'Suzuki', 'Alto', 2009, 2021, 'Front Brake Pads', 'Brakes', '55200-68L10', 'AKEBONO AN-760WK', 'Suzuki Genuine', 'Front brake pad set. Inspect every 20000km.', NULL, NULL),
(15, 'Honda', 'Fit', 2008, 2020, 'Engine Oil Filter', 'Filters', '15400-PLM-A02', 'DENSO 150-2013', 'Honda Genuine', 'Engine oil filter for L13A / L15A engine. Replace every 5000km.', NULL, NULL),
(16, 'Honda', 'Fit', 2008, 2020, 'Air Filter', 'Filters', '17220-RB0-000', 'DENSO DCF085', 'Honda Genuine', 'Air filter for L13A / L15A engine. Replace every 15000km.', NULL, NULL),
(17, 'Honda', 'Fit', 2008, 2020, 'Spark Plug', 'Spark Plugs', '98079-5614H', 'NGK IZFR6K-11', 'Honda Genuine', 'Iridium spark plug for L15A engine. Replace every 30000km.', NULL, NULL),
(18, 'Honda', 'Fit', 2008, 2020, 'Front Brake Pads', 'Brakes', '45022-SAA-G00', 'AKEBONO AN-800WK', 'Honda Genuine', 'Front brake pad set. Inspect every 20000km.', NULL, NULL),
(19, 'Toyota', 'Aqua', 2012, 2021, 'Engine Oil Filter', 'Filters', '04152-YZZA6', 'DENSO 150-2019', 'Toyota Genuine', 'Engine oil filter for 1NZ-FXE hybrid engine. Replace every 5000km.', NULL, NULL),
(20, 'Toyota', 'Aqua', 2012, 2021, 'Air Filter', 'Filters', '17801-21050', 'DENSO DCF146', 'Toyota Genuine', 'Air filter for 1NZ-FXE hybrid engine. Replace every 15000km.', NULL, NULL),
(21, 'Toyota', 'Aqua', 2012, 2021, 'Spark Plug', 'Spark Plugs', '90919-01249', 'NGK FK20HBR11', 'Toyota Genuine', 'Spark plug for 1NZ-FXE hybrid engine. Replace every 30000km.', NULL, NULL),
(22, 'Toyota', 'Aqua', 2012, 2021, 'Front Brake Pads', 'Brakes', '04465-52360', 'AKEBONO AN-4WK', 'Toyota Genuine', 'Front brake pad set for hybrid. Inspect every 20000km.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('loniperera2005@gmail.com', '$2y$12$SgS.K4n7B8S/xd1qFmM4C.P3FmX1uXpKuHRO0MVFZ39ECgbZhjA1i', '2026-03-28 19:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `service_logs`
--

CREATE TABLE `service_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `service_date` date NOT NULL,
  `mileage_at_service` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `garage_name` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `type` enum('maintenance','repair','inspection') NOT NULL DEFAULT 'maintenance',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_logs`
--

INSERT INTO `service_logs` (`id`, `vehicle_id`, `service_type`, `service_date`, `mileage_at_service`, `cost`, `garage_name`, `notes`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Engine Oil Change', '2026-03-04', 36000, 12000.00, 'AutoHub Lanka, Nugegoda', NULL, 'maintenance', '2026-03-04 11:50:30', '2026-03-04 11:50:30'),
(2, 1, 'Engine Oil Change', '2026-03-06', 44100, 12000.00, 'AutoHub Lanka, Nugegoda', NULL, 'maintenance', '2026-03-04 13:41:17', '2026-03-04 13:41:17'),
(3, 3, 'Engine Oil Change', '2026-03-07', 154000, 5000.00, 'AutoHub Lanka, Nugegoda', NULL, 'repair', '2026-03-07 03:19:28', '2026-03-07 03:19:28'),
(4, 2, 'Engine Oil Change', '2026-03-14', 78000, 7000.00, 'AutoHub Lanka, Nugegoda', NULL, 'inspection', '2026-03-14 15:31:47', '2026-03-14 15:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0uKuIFM2FNMX2xB5eaMnoGrF1fVIOgvk7NNMp7Ew', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUXo0cHk3QTZqVnhjNEFNdno3NEFOZWk0bjBFb1dSTXg3NWVYMWtKVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovL2xvY2FsaG9zdC9BdXRvTWF0ZVgvcHVibGljL3ZlaGljbGVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3QvQXV0b01hdGVYL3B1YmxpYy92ZWhpY2xlcyI7czo1OiJyb3V0ZSI7czoxNDoidmVoaWNsZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1774759288),
('RdEvRxBtZfQwzwJGAuXQcLxJGRoPy6dikfXb5acy', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlBTbzE4enV4NVpXRTdEVFF6NHVGWjdZZW5FWlIyYzdZV0d6eXVjSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODE6Imh0dHA6Ly9sb2NhbGhvc3QvQXV0b01hdGVYL3B1YmxpYy9wYXJ0cz9jYXRlZ29yeT0mbWFrZT1Ub3lvdGEmbW9kZWw9JnNlYXJjaD1icmFrZSI7czo1OiJyb3V0ZSI7czoxMToicGFydHMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1774760253);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('vehicle_owner','garage','admin') NOT NULL DEFAULT 'vehicle_owner',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Lonitha', 'loniperera2005@gmail.com', 'admin', NULL, '$2y$12$wOD5S2wyNdy5c2IW37ZW4OftSWP1X8GxYH9Zkj6CvcjINwaUKnJ2O', 'wToXonHu86fknbHYkVxfKmtcsAjEAlkTW3j0E8lRYoQP1pcmMJdx2qX7HdVp', '2026-03-04 09:25:48', '2026-03-28 19:22:22'),
(2, 'Kapila Perera', '123@gmail.com', 'vehicle_owner', NULL, '$2y$12$6JR./5xnf6u055XmDwxHSuw6eTQfLBI7V15B8aW7wrAKVzGFW0zca', NULL, '2026-03-07 02:18:06', '2026-03-28 22:09:07'),
(3, 'Shihan', 'shihan@gmail.com', 'vehicle_owner', NULL, '$2y$12$jBVDlmTp/iNOOSSpKBEzb.pjB.Fl/eUFX7ORQs7rGYllROhNUKfqK', NULL, '2026-03-14 15:44:24', '2026-03-28 22:12:33'),
(4, 'Test Garage', 'garage@automatex.com', 'garage', NULL, '$2y$12$rc0x7QyPOC0JtKN89Xkooux1coLnAy2c5nNNBE2DLULMjSllHGq8S', NULL, '2026-03-28 20:31:54', '2026-03-28 20:31:54'),
(5, 'Pasan', 'abc@gmail.com', 'vehicle_owner', NULL, '$2y$12$5Q809wLpdlZa6RhIoNR0luTqwD0g1DOz0F09PREqhJoP8u3k0R5HO', NULL, '2026-03-28 21:53:50', '2026-03-28 21:53:50'),
(6, 'Sanuga', '098@gmail.com', 'vehicle_owner', NULL, '$2y$12$xkQb0.keqBi3utSC.oikX.il7.fH9g0z/zfUCWttknlVDeiOBWtYu', NULL, '2026-03-28 21:57:34', '2026-03-28 21:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `make` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `mileage` int(11) NOT NULL DEFAULT 0,
  `vin` varchar(255) DEFAULT NULL,
  `qr_token` varchar(255) DEFAULT NULL,
  `license_plate` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `fuel_type` varchar(255) NOT NULL DEFAULT 'petrol',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `user_id`, `make`, `model`, `year`, `mileage`, `vin`, `qr_token`, `license_plate`, `color`, `fuel_type`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Toyota', 'Premio', 2018, 44100, 'JT2AE09W6H3456789', 'e016c3ef-54d7-4266-8b0a-2b2de5b9f89a', 'CAY-8485', 'Pearl White', 'petrol', NULL, '2026-03-04 09:46:23', '2026-03-04 13:50:12'),
(2, 1, 'Honda', 'Civic', 2015, 78000, 'JT1AE99W6H3456345', '6458a9a2-b497-436a-aeab-323a0509fb8c', 'KK-6677', 'Blue', 'petrol', NULL, '2026-03-04 16:01:08', '2026-03-04 16:01:08'),
(3, 1, 'Toyota', 'Prius', 2015, 154000, NULL, '76e282f6-dde4-40bd-9410-9630589f7fb3', 'KW-2244', 'Res', 'hybrid', NULL, '2026-03-07 03:16:54', '2026-03-07 03:16:54'),
(4, 2, 'Toyota', 'Axio', 2013, 68000, '234567dfgh', 'a2ff488c-2dd0-40c5-953e-d676a31116d6', 'KK-9860', 'Blue', 'diesel', NULL, '2026-03-28 22:26:04', '2026-03-28 23:19:39'),
(5, 2, 'Toyota', 'Axio', 2003, 25010, '567yfjyif87', 'f281e062-366d-4a5a-b3bd-63eaef2c2b34', 'Kh-9861', 'Pearl White', 'hybrid', NULL, '2026-03-28 22:28:53', '2026-03-28 23:23:02'),
(6, 2, 'Toyota', 'Axio', 2006, 123457, NULL, '2ed58711-4781-49fd-865d-c68346f022d1', 'KK-6670', 'Blue', 'hybrid', NULL, '2026-03-28 22:32:56', '2026-03-28 22:32:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `bookings_garage_id_foreign` (`garage_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuel_logs_vehicle_id_foreign` (`vehicle_id`);

--
-- Indexes for table `garages`
--
ALTER TABLE `garages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `garages_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `service_logs`
--
ALTER TABLE `service_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_logs_vehicle_id_foreign` (`vehicle_id`);

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
  ADD UNIQUE KEY `vehicles_vin_unique` (`vin`),
  ADD UNIQUE KEY `vehicles_qr_token_unique` (`qr_token`),
  ADD KEY `vehicles_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `garages`
--
ALTER TABLE `garages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_schedules`
--
ALTER TABLE `maintenance_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `service_logs`
--
ALTER TABLE `service_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_garage_id_foreign` FOREIGN KEY (`garage_id`) REFERENCES `garages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD CONSTRAINT `fuel_logs_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `garages`
--
ALTER TABLE `garages`
  ADD CONSTRAINT `garages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_logs`
--
ALTER TABLE `service_logs`
  ADD CONSTRAINT `service_logs_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
