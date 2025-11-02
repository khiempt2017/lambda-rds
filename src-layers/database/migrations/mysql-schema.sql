-- MySQL Schema Migration
-- BP-api-serverless
-- Created: 2025/10/07
-- This file contains the schema for migrating from DynamoDB to MySQL

-- Mails Table
CREATE TABLE IF NOT EXISTS `mails` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `record_id` BIGINT NOT NULL,
  `nick` VARCHAR(255) NULL,
  `authkey` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `is_self` TINYINT NOT NULL DEFAULT 0,
  `agree` TINYINT NOT NULL DEFAULT 0,
  `email` VARCHAR(255) NULL,
  `allow` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_record` (`user_id`, `record_id`),
  KEY `idx_authkey` (`authkey`),
  KEY `idx_user_created` (`user_id`, `created_at`),
  KEY `idx_user_is_self` (`user_id`, `is_self`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Add indexes for performance
-- Already added above in table definitions

-- Insert dummy data for categories
INSERT IGNORE INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Apple', 'Apple laptops and devices', 1, NOW(), NOW()),
(2, 'HP', 'HP laptops and computers', 1, NOW(), NOW()),
(3, 'Lenovo', 'Lenovo laptops and computers', 1, NOW(), NOW());

-- Notes:
-- 1. All tables use InnoDB engine for ACID compliance and foreign key support
-- 2. UTF8MB4 charset for full Unicode support including emojis
-- 3. Auto-incrementing ID as primary key for better performance
-- 4. Original DynamoDB keys are preserved as unique indexes
-- 5. Timestamps are stored as DATETIME for consistency
-- 6. Adjust field types and sizes as needed for your specific use case
-- 7. Products table has foreign key relationship with categories table

