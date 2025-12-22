/*
SQLyog Ultimate v10.42 
MySQL - 8.0.44-0ubuntu0.22.04.1 : Database - u1721210_general_ledger
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `account_centers` */

CREATE TABLE `account_centers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_coa` int NOT NULL COMMENT 'hanya ledger dan sub ledger yanga da di tabel ini',
  `code_cc` int NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_coa_cc_company` (`code_coa`,`code_cc`,`code_company`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `group_index_1` (`code_coa`,`code_company`),
  KEY `index_uuid` (`uuid`),
  KEY `index_coa` (`code_coa`),
  KEY `index_company` (`code_company`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `audit_list` */

CREATE TABLE `audit_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_create` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` enum('CREATED','UPDATED','DELETED','SYSTEM_UPDATED','UPDATE_STATUS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_id` int DEFAULT NULL,
  `latest_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_item_id` int DEFAULT NULL,
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `audit_list_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `budgeting_headers` */

CREATE TABLE `budgeting_headers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_budgeting` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_department` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` int NOT NULL,
  `extend_budget` int DEFAULT NULL,
  `project_amount` int NOT NULL,
  `years` int DEFAULT NULL,
  `date_budgeting` date DEFAULT NULL,
  `status_budgeting` enum('OPEN','APPROVED','CLOSED','REVIEW','REJECT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_company` (`code_company`),
  KEY `index_uuid` (`uuid`),
  KEY `index_department` (`uuid`,`code_department`),
  KEY `index_budget` (`code_budgeting`),
  KEY `index_status` (`status_budgeting`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `budgeting_project_items` */

CREATE TABLE `budgeting_project_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code_budgeting` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_item` int NOT NULL COMMENT 'untuk join ke header project',
  `itemnumber` int NOT NULL COMMENT 'jumlah item',
  `type_goal` enum('REDUCE','IMPROVE','OPEX','CAPEX') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'OPEX : dengan akun | CAPEX : no akun',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `account_number` int DEFAULT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `budgeting_projects` */

CREATE TABLE `budgeting_projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code_budgeting` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_item` int NOT NULL,
  `project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goal_project` enum('REDUCE','IMPROVE','ALL') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tujuan Proyek',
  `project_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `budget_proposal` int DEFAULT NULL COMMENT 'usulan anggaran',
  `filename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `budgeting_verify` */

CREATE TABLE `budgeting_verify` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_budgeting` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_budgeting` enum('APPROVED','REJECT') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_date` date DEFAULT NULL,
  `verification_notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_created` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_uuid` (`uuid`),
  KEY `index_code` (`code_budgeting`),
  KEY `index_status` (`status_budgeting`),
  KEY `index_company` (`code_company`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `chart_of_accounts` */

CREATE TABLE `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` int NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_header` int DEFAULT NULL COMMENT 'mengacu sebagai ledger',
  `code_ledger` int DEFAULT NULL COMMENT 'mengacu sebagai subledger',
  `name` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('asset','liability','equity','income','expense','retained earning account') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_method` enum('debit','credit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_group` enum('kas','bank','inventory','sales') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_center_type` enum('depo','unit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_category` enum('header','ledger','subledger') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_trialbalance1` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_trialbalance2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_trialbalance3` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `acno_company_code_unik` (`account_number`,`code_company`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `index_code_company` (`code_company`),
  KEY `index_account_number` (`account_number`),
  KEY `index_code_trialbalance1` (`code_trialbalance1`),
  KEY `index_code_trialbalance2` (`code_trialbalance2`),
  KEY `index_code_trialbalance3` (`code_trialbalance3`),
  KEY `index_account_category` (`account_category`),
  KEY `index_header` (`code_header`),
  KEY `index_ledger` (`code_ledger`),
  KEY `index_uuid` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `companies` */

CREATE TABLE `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `owner_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_sign_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_uuid_unique` (`uuid`),
  UNIQUE KEY `companies_name_unique` (`name`),
  UNIQUE KEY `companies_code_company_unique` (`code_company`),
  KEY `group_index_I` (`name`,`code_company`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cost_centers` */

CREATE TABLE `cost_centers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_cost_center` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_team` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_depo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_department` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_divisi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_segment` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_area` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cost_centers_uuid_unique` (`uuid`),
  KEY `group_index_I` (`code_cost_center`,`code_depo`,`code_divisi`,`code_department`,`code_segment`),
  KEY `cost_centers_code_cost_center_index` (`code_cost_center`),
  KEY `cost_centers_group_team_index` (`group_team`),
  KEY `cost_centers_code_depo_index` (`code_depo`),
  KEY `cost_centers_code_department_index` (`code_department`),
  KEY `cost_centers_code_divisi_index` (`code_divisi`),
  KEY `cost_centers_code_segment_index` (`code_segment`),
  KEY `cost_centers_code_company_index` (`code_company`),
  KEY `cost_centers_code_area_index` (`code_area`),
  KEY `group_index_2` (`code_cost_center`,`code_company`),
  CONSTRAINT `cost_centers_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_department_foreign` FOREIGN KEY (`code_department`) REFERENCES `departments` (`code_department`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_depo_foreign` FOREIGN KEY (`code_depo`) REFERENCES `depos` (`code_depo`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_divisi_foreign` FOREIGN KEY (`code_divisi`) REFERENCES `divisions` (`code_divisi`) ON DELETE RESTRICT,
  CONSTRAINT `cost_centers_code_segment_foreign` FOREIGN KEY (`code_segment`) REFERENCES `segments` (`code_segment`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=954 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `counters` */

CREATE TABLE `counters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prefix` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `last_number` int NOT NULL DEFAULT '0',
  `years` year DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `index_prefix` (`prefix`),
  KEY `index_years` (`years`),
  KEY `group_index` (`prefix`,`years`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `departments` */

CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_department` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `status_data` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_uuid_unique` (`uuid`),
  KEY `departments_code_department_index` (`code_department`),
  KEY `departments_alias_index` (`alias`),
  KEY `departments_code_company_index` (`code_company`),
  KEY `departments_status_data_index` (`status_data`),
  CONSTRAINT `departments_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `depos` */

CREATE TABLE `depos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `npwp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_area` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'area kode',
  `fiscal_year` int DEFAULT NULL,
  `status_depo` enum('pusat','depo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `depos_uuid_unique` (`uuid`),
  KEY `group_index_I` (`code_depo`,`code_company`),
  KEY `depos_code_depo_index` (`code_depo`),
  KEY `depos_alias_index` (`alias`),
  KEY `depos_code_company_index` (`code_company`),
  KEY `depos_code_area_index` (`code_area`),
  KEY `depos_status_depo_index` (`status_depo`),
  CONSTRAINT `depos_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `detail_audit_list` */

CREATE TABLE `detail_audit_list` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `audit_list_id` int NOT NULL,
  `journal_id` int DEFAULT NULL,
  `voucher_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `detail_audit_list_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `divisions` */

CREATE TABLE `divisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_divisi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_uuid_unique` (`uuid`),
  KEY `divisions_code_company_foreign` (`code_company`),
  KEY `divisions_code_divisi_index` (`code_divisi`),
  KEY `divisions_alias_index` (`alias`),
  CONSTRAINT `divisions_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `fiscal_periods` */

CREATE TABLE `fiscal_periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `period` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('closed','open','undefined') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'undefined',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fiscal_periods_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `journal_items` */

CREATE TABLE `journal_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence_header` int DEFAULT NULL,
  `sequence_number` int NOT NULL,
  `batch_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_cost_center` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_coa` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` bigint NOT NULL,
  `credit` bigint NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `dpp` bigint DEFAULT '0',
  `ppn` bigint DEFAULT '0',
  `total_tax` bigint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_items_uuid_unique` (`uuid`),
  KEY `idx_batch` (`batch_number`),
  KEY `idx_coa_costcenter` (`code_coa`,`code_cost_center`),
  KEY `idx_transaction_date` (`transaction_date`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `journal_sources` */

CREATE TABLE `journal_sources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_journal_source` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_depo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journal_sources_uuid_unique` (`uuid`),
  KEY `group_index_I` (`code_journal_source`,`code_depo`,`code_company`),
  KEY `journal_sources_code_journal_source_index` (`code_journal_source`),
  KEY `journal_sources_code_depo_index` (`code_depo`),
  KEY `journal_sources_code_company_index` (`code_company`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `journals` */

CREATE TABLE `journals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sequence` int NOT NULL,
  `batch_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code_depo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_journal_source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('posted','unposted') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `year` year NOT NULL,
  `period` tinyint unsigned NOT NULL,
  `total_credit` bigint DEFAULT NULL,
  `total_debit` bigint DEFAULT NULL,
  `difference` bigint DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_create` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `journals_uuid_unique` (`uuid`),
  KEY `idx_journal_period` (`year`,`period`,`code_journal_source`,`code_depo`),
  KEY `idx_voucher` (`voucher_number`),
  KEY `idx_batch` (`batch_number`),
  KEY `idx_period` (`code_depo`,`year`,`period`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `menus` */

CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `parent_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `is_active` int DEFAULT '1' COMMENT '1 = active | 0 not active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `index_menu_id` (`id`),
  KEY `index_is_active` (`is_active`),
  CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `migrations` */

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `petty_cash_banks` */

CREATE TABLE `petty_cash_banks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `voucher_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `account_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `item_number` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `petty_cash_headers` */

CREATE TABLE `petty_cash_headers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `voucher_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nomor Transaksi (Voucher)',
  `trans_date` date DEFAULT NULL,
  `proveniance` enum('CASH','BANK') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `flow` enum('IN','OUT') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('APPLIED','VOID') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_at` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_up` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_voucher` (`voucher_no`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `petty_cash_itemprices` */

CREATE TABLE `petty_cash_itemprices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `voucher_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `account_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `debit` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `credit` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `item_number` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_voucher` (`voucher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `posting_balances` */

CREATE TABLE `posting_balances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_depo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year NOT NULL,
  `period` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_cost_center` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_coa` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` bigint NOT NULL,
  `debit` bigint NOT NULL,
  `credit` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posting_balances_uuid_unique` (`uuid`),
  KEY `index_year` (`year`),
  KEY `index_code_depo` (`code_depo`),
  KEY `index_code_cost_center` (`code_cost_center`),
  KEY `index_code_coa` (`code_coa`),
  KEY `index_code_company` (`code_company`),
  KEY `index_group_1` (`code_depo`,`year`,`code_cost_center`,`code_coa`,`code_company`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `product_mapped` */

CREATE TABLE `product_mapped` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `transaction_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `operational_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost_center_id` int NOT NULL,
  `coa_id` int NOT NULL,
  `kind` enum('debit','credit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `do_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_mapped_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `products` */

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `coa_id` int DEFAULT NULL,
  `company_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `role_menu_access` */

CREATE TABLE `role_menu_access` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `group_1` (`role_id`,`menu_id`),
  CONSTRAINT `role_menu_access_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_menu_access_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `roles` */

CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `segments` */

CREATE TABLE `segments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_segment` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'kode perusahaan',
  `status_data` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `segments_uuid_unique` (`uuid`),
  KEY `segments_code_segment_index` (`code_segment`),
  KEY `segments_alias_index` (`alias`),
  KEY `segments_code_company_index` (`code_company`),
  KEY `segments_status_data_index` (`status_data`),
  CONSTRAINT `segments_code_company_foreign` FOREIGN KEY (`code_company`) REFERENCES `companies` (`code_company`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trial_balance_account_group_1` */

CREATE TABLE `trial_balance_account_group_1` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_trialbalance1` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_type` enum('asset','liability','equity','income','expense','retained earning account') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_balance_account_group_1_uuid_unique` (`uuid`),
  KEY `index_deskripsi` (`description`),
  KEY `index_akun_type` (`account_type`),
  KEY `code_trialbalance1` (`code_trialbalance1`),
  KEY `code_company` (`code_company`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trial_balance_account_group_2` */

CREATE TABLE `trial_balance_account_group_2` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_trialbalance2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_trialbalance1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_balance_account_group_2_uuid_unique` (`uuid`),
  KEY `index_code` (`code_company`,`code_trialbalance1`),
  KEY `index_desc` (`description`),
  KEY `index_code_tb1` (`code_trialbalance1`),
  KEY `index_company` (`code_company`),
  KEY `index_group` (`code_company`,`code_trialbalance2`,`code_trialbalance1`),
  KEY `index_tbag2` (`code_trialbalance2`),
  CONSTRAINT `tbag2_join_tbag1` FOREIGN KEY (`code_trialbalance1`) REFERENCES `trial_balance_account_group_1` (`code_trialbalance1`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `trial_balance_account_group_3` */

CREATE TABLE `trial_balance_account_group_3` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_trialbalance3` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_trialbalance1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_trialbalance2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trial_balance_account_group_3_uuid_unique` (`uuid`),
  KEY `index_tbag1` (`code_trialbalance1`),
  KEY `index_tbag2` (`code_trialbalance2`),
  KEY `index_tbag3` (`code_trialbalance3`),
  KEY `code_company` (`code_company`),
  CONSTRAINT `tbag3_join_tbag1` FOREIGN KEY (`code_trialbalance1`) REFERENCES `trial_balance_account_group_1` (`code_trialbalance1`) ON DELETE RESTRICT,
  CONSTRAINT `tbag3_join_tbag2` FOREIGN KEY (`code_trialbalance2`) REFERENCES `trial_balance_account_group_2` (`code_trialbalance2`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_password_default` tinyint(1) NOT NULL DEFAULT '0',
  `code_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_reset_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_uuid_unique` (`uuid`),
  KEY `role_id` (`role_id`),
  KEY `index_code_company` (`code_company`),
  CONSTRAINT `role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
