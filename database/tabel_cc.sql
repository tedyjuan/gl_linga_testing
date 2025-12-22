CREATE TABLE `u1721210_general_ledger`.`account_centers` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`code_coa` VARCHAR(50) NOT NULL,
	`code_cc` VARCHAR(50) NOT NULL,
	`code_company` VARCHAR(50) NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE KEY `uq_coa_cc_company` (`code_coa`, `code_cc`, `code_company`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
