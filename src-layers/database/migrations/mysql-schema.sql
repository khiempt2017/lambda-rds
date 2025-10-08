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

-- Regular Report Table
CREATE TABLE IF NOT EXISTS `regular_report` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `logout_flg` TINYINT NOT NULL DEFAULT 0,
  `auto_send` TINYINT NOT NULL DEFAULT 0,
  `send_day` VARCHAR(50) NULL,
  `send_time` VARCHAR(10) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_id` (`user_id`),
  KEY `idx_logout_auto_send` (`logout_flg`, `auto_send`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Diaries Table
CREATE TABLE IF NOT EXISTS `diaries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `morning_systolic` INT NULL,
  `morning_diastolic` INT NULL,
  `morning_pulse` INT NULL,
  `evening_systolic` INT NULL,
  `evening_diastolic` INT NULL,
  `evening_pulse` INT NULL,
  `weight` DECIMAL(5,2) NULL,
  `memo` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_date` (`user_id`, `date`),
  KEY `idx_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banner Management Table
CREATE TABLE IF NOT EXISTS `banner_management` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `banner_category` INT NOT NULL,
  `is_displayed` TINYINT NOT NULL DEFAULT 1,
  `display_count` INT NOT NULL DEFAULT 0,
  `last_displayed_at` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_banner` (`user_id`, `banner_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Common Settings Table
CREATE TABLE IF NOT EXISTS `common_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `common_key` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_common_key` (`common_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Memo Orders Table
CREATE TABLE IF NOT EXISTS `memo_orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `memo_order` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `notification_enabled` TINYINT NOT NULL DEFAULT 1,
  `language` VARCHAR(10) NOT NULL DEFAULT 'ja',
  `timezone` VARCHAR(50) NOT NULL DEFAULT 'Asia/Tokyo',
  `theme` VARCHAR(50) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Withdrawal User Table
CREATE TABLE IF NOT EXISTS `withdrawal_user` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` VARCHAR(255) NOT NULL,
  `withdrawal_reason` TEXT NULL,
  `withdrawal_date` DATETIME NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_id` (`user_id`),
  KEY `idx_withdrawal_date` (`withdrawal_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for performance
-- Already added above in table definitions

-- Notes:
-- 1. All tables use InnoDB engine for ACID compliance and foreign key support
-- 2. UTF8MB4 charset for full Unicode support including emojis
-- 3. Auto-incrementing ID as primary key for better performance
-- 4. Original DynamoDB keys are preserved as unique indexes
-- 5. Timestamps are stored as DATETIME for consistency
-- 6. Adjust field types and sizes as needed for your specific use case

