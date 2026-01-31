# ************************************************************
# Sequel Ace SQL dump
# Version 20096
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 11.8.2-MariaDB)
# Database: get_trade
# Generation Time: 2026-01-30 10:10:19 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table dividend_withdrawal_history_closings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dividend_withdrawal_history_closings`;

CREATE TABLE `dividend_withdrawal_history_closings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `closing_date` date NOT NULL,
  `status` enum('pending','processing','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dividend_withdrawal_history_closings_closing_date_unique` (`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `plan_id` bigint(20) unsigned NOT NULL,
  `deposit_transaction_id` bigint(20) unsigned NOT NULL,
  `amount_in_usd` decimal(40,2) NOT NULL,
  `coin` varchar(255) DEFAULT 'bep20_usdt',
  `status` enum('pending','success','failure') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table job_batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` text NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table kyc_submissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kyc_submissions`;

CREATE TABLE `kyc_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `kyc_id` bigint(20) unsigned NOT NULL,
  `aadhaar_number` varchar(255) DEFAULT NULL,
  `aadhaar_front` varchar(255) DEFAULT NULL,
  `aadhaar_back` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `pan_file` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `cancel_cheque` varchar(255) DEFAULT NULL,
  `upi_id` varchar(255) DEFAULT NULL,
  `upi_number` varchar(255) DEFAULT NULL,
  `status` enum('submitted','approved','rejected') NOT NULL DEFAULT 'submitted',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table level_withdrawal_history_closings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `level_withdrawal_history_closings`;

CREATE TABLE `level_withdrawal_history_closings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `closing_date` date NOT NULL,
  `status` enum('pending','processing','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `level_withdrawal_history_closings_closing_date_unique` (`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table otps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `otps`;

CREATE TABLE `otps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `code` int(11) NOT NULL,
  `expire_at` datetime NOT NULL,
  `is_used` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table plans
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plans`;

CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `amount` decimal(40,2) NOT NULL DEFAULT 0.00,
  `monthly_roi_amount` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `tenure` decimal(40,2) NOT NULL DEFAULT 0.00,
  `principle_amount` decimal(40,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `allow_to_user` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table reward_income_closings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reward_income_closings`;

CREATE TABLE `reward_income_closings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `closing_date` date NOT NULL,
  `status` enum('pending','processing','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reward_income_closings_closing_date_unique` (`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table rewards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rewards`;

CREATE TABLE `rewards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `business` decimal(8,2) NOT NULL,
  `reward_amount` decimal(8,2) NOT NULL,
  `reward_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table roi_income_closings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roi_income_closings`;

CREATE TABLE `roi_income_closings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `closing_date` date NOT NULL,
  `status` enum('pending','processing','success','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roi_income_closings_closing_date_unique` (`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table site_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `site_settings`;

CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `autoload` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table subscriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `plan_id` int(11) NOT NULL,
  `user_usd_wallet_transaction_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `amount` decimal(40,2) NOT NULL,
  `tenure_end_date` date NOT NULL,
  `maturity_date` date NOT NULL,
  `lock_end_date` date NOT NULL,
  `earned_so_far` decimal(40,4) NOT NULL,
  `working_earned_so_far` decimal(40,4) NOT NULL,
  `passive_earned_so_far` decimal(40,4) NOT NULL,
  `max_income_limit` decimal(40,4) NOT NULL,
  `working_income_limit` decimal(40,4) NOT NULL,
  `passive_income_limit` decimal(40,4) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table teams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams` (
  `id` char(36) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `direct` bigint(20) unsigned NOT NULL DEFAULT 0,
  `active_direct` bigint(20) unsigned NOT NULL DEFAULT 0,
  `total` bigint(20) unsigned NOT NULL DEFAULT 0,
  `active_total` bigint(20) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table trees
# ------------------------------------------------------------

DROP TABLE IF EXISTS `trees`;

CREATE TABLE `trees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `sponsor_id` bigint(20) unsigned NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_actives
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_actives`;

CREATE TABLE `user_actives` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `active_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_actives_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_businesses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_businesses`;

CREATE TABLE `user_businesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(40,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_businesses_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_daily_businesses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_daily_businesses`;

CREATE TABLE `user_daily_businesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `business_date` date NOT NULL,
  `team_business` decimal(15,2) NOT NULL DEFAULT 0.00,
  `direct_business` decimal(15,2) NOT NULL DEFAULT 0.00,
  `self_business` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_achieved` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_daily_businesses_user_id_business_date_unique` (`user_id`,`business_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_daily_income_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_daily_income_stats`;

CREATE TABLE `user_daily_income_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `income_date` date NOT NULL,
  `income_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_daily_income_stats_user_id_income_date_unique` (`user_id`,`income_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_direct_incomes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_direct_incomes`;

CREATE TABLE `user_direct_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `from_user` bigint(20) NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `income` decimal(40,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_fund_add_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_fund_add_requests`;

CREATE TABLE `user_fund_add_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `payment_type` enum('Cash','Cheque','NEFT','RTGS','UPI') NOT NULL DEFAULT 'UPI',
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_proof` varchar(255) DEFAULT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_income_on_holds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_income_on_holds`;

CREATE TABLE `user_income_on_holds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `direct` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `level` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `roi` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `roi_on_roi` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `rank` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `bonanza` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `reward` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_income_on_holds_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_income_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_income_stats`;

CREATE TABLE `user_income_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `direct` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `level` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `roi` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `roi_on_roi` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `rank` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `bonanza` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `reward` decimal(40,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_income_stats_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_income_wallets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_income_wallets`;

CREATE TABLE `user_income_wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(40,2) NOT NULL DEFAULT 0.00,
  `balance_on_hold` decimal(40,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_income_wallets_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_kycs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_kycs`;

CREATE TABLE `user_kycs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `aadhaar_number` varchar(255) DEFAULT NULL,
  `aadhaar_front` varchar(255) DEFAULT NULL,
  `aadhaar_back` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `pan_file` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `cancel_cheque` varchar(255) DEFAULT NULL,
  `upi_id` varchar(255) DEFAULT NULL,
  `upi_number` varchar(255) DEFAULT NULL,
  `current_step` tinyint(4) NOT NULL DEFAULT 1,
  `status` enum('pending','submitted','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_kycs_user_id_unique` (`user_id`),
  CONSTRAINT `user_kycs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_leg_businesses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_leg_businesses`;

CREATE TABLE `user_leg_businesses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `direct_user_id` bigint(20) NOT NULL,
  `amount` decimal(40,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_leg_businesses_user_id_direct_user_id_unique` (`user_id`,`direct_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_level_income_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_level_income_stats`;

CREATE TABLE `user_level_income_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `level` int(11) NOT NULL,
  `income_percent` decimal(20,4) NOT NULL,
  `income_amount` decimal(40,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_level_incomes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_level_incomes`;

CREATE TABLE `user_level_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `income_date` date NOT NULL,
  `amount` decimal(40,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_level_incomes_user_id_income_date_unique` (`user_id`,`income_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_level_roi_incomes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_level_roi_incomes`;

CREATE TABLE `user_level_roi_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `user_roi_income_id` bigint(20) NOT NULL DEFAULT 0,
  `level` int(11) DEFAULT 0,
  `income_percent` decimal(20,8) NOT NULL,
  `income_usd` decimal(40,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_level_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_level_stats`;

CREATE TABLE `user_level_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `downline_user_id` bigint(20) unsigned NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_level_stats_unique` (`user_id`,`downline_user_id`,`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_levels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_levels`;

CREATE TABLE `user_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `level` int(11) NOT NULL,
  `team` int(11) NOT NULL,
  `active_team` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_rank_roi_incomes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_rank_roi_incomes`;

CREATE TABLE `user_rank_roi_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `user_roi_income_id` bigint(20) unsigned NOT NULL,
  `rank` tinyint(3) unsigned NOT NULL,
  `income_percent` decimal(5,2) NOT NULL,
  `income` decimal(18,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_rank_roi_per_day` (`user_id`,`user_roi_income_id`),
  KEY `user_rank_roi_incomes_user_id_index` (`user_id`),
  KEY `user_rank_roi_incomes_user_roi_income_id_index` (`user_roi_income_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_ranks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_ranks`;

CREATE TABLE `user_ranks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `rank` int(10) unsigned NOT NULL,
  `achieved_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_ranks_user_id_unique` (`user_id`),
  CONSTRAINT `user_ranks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_reward_income_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_reward_income_stats`;

CREATE TABLE `user_reward_income_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `reward_income_closing_id` bigint(20) unsigned NOT NULL,
  `reward_id` bigint(20) unsigned NOT NULL,
  `income_usd` decimal(40,4) NOT NULL,
  `reward_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_reward_ranks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_reward_ranks`;

CREATE TABLE `user_reward_ranks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `rank` int(11) NOT NULL,
  `is_achieved` tinyint(4) NOT NULL DEFAULT 0,
  `is_credited` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_roi_incomes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_roi_incomes`;

CREATE TABLE `user_roi_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `subscription_id` bigint(20) NOT NULL,
  `closing_date` date NOT NULL,
  `amount` decimal(40,4) NOT NULL,
  `income` decimal(40,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roi_incomes_subscription_id_closing_date_unique` (`subscription_id`,`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_stops
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_stops`;

CREATE TABLE `user_stops` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_blocked` tinyint(4) DEFAULT 0,
  `direct` tinyint(4) DEFAULT 0,
  `roi` tinyint(4) DEFAULT 0,
  `roi_on_roi` tinyint(4) DEFAULT 0,
  `rank` tinyint(4) DEFAULT 0,
  `bonanza` tinyint(4) DEFAULT 0,
  `reward` tinyint(4) DEFAULT 0,
  `withdrawal` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_stops_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_usd_wallet_admin_updates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_usd_wallet_admin_updates`;

CREATE TABLE `user_usd_wallet_admin_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(40,4) NOT NULL,
  `previous_amount` decimal(40,4) NOT NULL,
  `type` enum('debit','credit') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_usd_wallet_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_usd_wallet_transactions`;

CREATE TABLE `user_usd_wallet_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `transaction_type` enum('debit','credit') NOT NULL,
  `amount_in_usd` decimal(40,2) NOT NULL,
  `last_amount` decimal(40,2) NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `status` enum('success','pending','fail','hold') NOT NULL,
  `txn_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_usd_wallets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_usd_wallets`;

CREATE TABLE `user_usd_wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `balance` decimal(40,2) NOT NULL DEFAULT 0.00,
  `balance_on_hold` decimal(40,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_usd_wallets_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_wallet_ledgers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_wallet_ledgers`;

CREATE TABLE `user_wallet_ledgers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `wallet_type` enum('USDT Wallet','Income Wallet') DEFAULT NULL,
  `last_amount` decimal(40,8) DEFAULT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'INR',
  `txn_type` enum('Debit','Credit') NOT NULL DEFAULT 'Debit',
  `amount` decimal(40,8) DEFAULT NULL,
  `new_amount` decimal(40,8) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table user_weekly_team_with_business_stats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_weekly_team_with_business_stats`;

CREATE TABLE `user_weekly_team_with_business_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `direct_active` int(11) NOT NULL DEFAULT 0,
  `team_active` int(11) NOT NULL DEFAULT 0,
  `direct_business` decimal(48,2) NOT NULL DEFAULT 0.00,
  `team_business` decimal(48,2) NOT NULL DEFAULT 0.00,
  `is_pool_active` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_weekly_team_with_business_stats_user_id_start_date_unique` (`user_id`,`start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ref_code` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `sponsor_id` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `position` enum('LEFT','RIGHT') DEFAULT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `country_code` varchar(5) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `state_code` varchar(10) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `active_at` timestamp NULL DEFAULT NULL,
  `is_blocked` tinyint(4) NOT NULL DEFAULT 0,
  `welcome_seen_at` timestamp NULL DEFAULT NULL,
  `welcome_mode` enum('every_login','weekly','monthly','once','never') NOT NULL DEFAULT 'once',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_ref_code_unique` (`ref_code`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table voucher_transactions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `voucher_transactions`;

CREATE TABLE `voucher_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(40,2) NOT NULL,
  `txn_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voucher_transactions_txn_id_unique` (`txn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table withdrawal_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `withdrawal_histories`;

CREATE TABLE `withdrawal_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_ifsc` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(255) DEFAULT NULL,
  `upi_id` varchar(255) DEFAULT NULL,
  `upi_number` varchar(255) DEFAULT NULL,
  `fees` decimal(40,2) NOT NULL,
  `amount` decimal(40,2) NOT NULL,
  `receivable_amount` decimal(40,2) NOT NULL,
  `status` enum('pending','processing','failed','success') NOT NULL,
  `type` enum('level','dividend','maturity') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table withdrawal_temps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `withdrawal_temps`;

CREATE TABLE `withdrawal_temps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `fees` decimal(40,8) NOT NULL,
  `amount` decimal(40,8) NOT NULL,
  `receivable_amount` decimal(40,8) NOT NULL,
  `status` enum('pending','processing','failed','success') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
