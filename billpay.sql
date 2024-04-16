-- user table
CREATE TABLE `user`(
                       `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                       `first_name` VARCHAR(50) NOT NULL,
                       `last_name` VARCHAR(50) NOT NULL,
                       `user_name` VARCHAR(50) NOT NULL,
                       `email` VARCHAR(50) NOT NULL UNIQUE,
                       `password` VARCHAR(150) NOT NULL,
                       `salt` VARCHAR(150) NOT NULL,
                       `gender` ENUM('male', 'female') NOT NULL,
                       `mobile` VARCHAR(50) NOT NULL UNIQUE,
                       `image` VARCHAR(50) NULL,
                       `type` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                       `status` ENUM('active', 'pending', 'deleted', '') NOT NULL DEFAULT 'pending',
                       `auth_token` VARCHAR(20) NOT NULL,
                       `api_key` VARCHAR(150) NOT NULL,
                       `ref_id` VARCHAR(10) NOT NULL,
                       `reg_date` TIMESTAMP,
                       `date_created` TIMESTAMP NOT NULL,
                       `country` VARCHAR(100) DEFAULT NULL,
                       `state` VARCHAR(100) DEFAULT NULL,
                       `city` VARCHAR(100) DEFAULT NULL,
                       `currency` VARCHAR(3) DEFAULT NULL,
                        `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- user_wallet table
CREATE TABLE `user_wallet`(
                              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                              `user_id` INT(11) NOT NULL,
                              `wallet_balance` DECIMAL(11, 2) NOT NULL DEFAULT '0.00',
                              `date_created` TIMESTAMP NOT NULL,
                              `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- user_settings table
CREATE TABLE `user_setting`(
                               `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                               `user_id` INT(11) NOT NULL,
                               `date_created` TIMESTAMP NOT NULL,
                               `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                               FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- INSERT INTO `user_wallet` (`id`, `user_id`, `wallet_balance`, `date_created`, `date_updated`) VALUES ('1', '1', '0.00', NOW(), current_timestamp());


-- recharge_transactions table
CREATE TABLE recharge_transaction (
                                      `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                                      `user_id` INT(11) NOT NULL,
                                      FOREIGN KEY (user_id) REFERENCES user(id),
                                      `vendor` VARCHAR(100) NOT NULL,
                                      `request_id` VARCHAR(50) NOT NULL,
                                      `transaction_id` VARCHAR(50),
                                      `vendor_response` TEXT,
                                      `status` ENUM('pending', 'success', 'failure') NOT NULL,
                                      `phone_number` VARCHAR(20),
                                      `transaction_amount` DECIMAL(10, 2) NOT NULL,
                                      `transaction_type` ENUM('airtime', 'data', 'electricity'),
                                      `payment_method` ENUM('credit_card', 'bank_transfer', 'wallet', 'cash'),
                                      `date_created` TIMESTAMP NOT NULL,
                                      `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- service table
CREATE TABLE `service`(
                          `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                          `name` VARCHAR(50) NOT NULL UNIQUE,
                          `code` VARCHAR(50) NOT NULL UNIQUE ,
                          `status` ENUM('true', 'false') NOT NULL,
                          `description` TEXT,
                          `image` VARCHAR(50) NULL,
                          `api` INT(11) NULL,
                          `date_created` TIMESTAMP NOT NULL,
                          `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- services options table
CREATE TABLE `service_option`(
                                 `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `service_id` INT(11) NOT NULL,
                                 FOREIGN KEY (service_id) REFERENCES service(id),
                                 `name` VARCHAR(50) NOT NULL UNIQUE,
                                 `code` VARCHAR(50) NOT NULL UNIQUE ,
                                 `status` ENUM('true', 'false') NOT NULL,
                                 `description` TEXT,
                                 `image` VARCHAR(50) NULL,
                                 `api` INT(11) NULL,
                                 `date_created` TIMESTAMP NOT NULL,
                                 `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- services option codes table
CREATE TABLE `service_option_codes`(
                                       `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                       `service_option_id` INT(11) NOT NULL,
                                       FOREIGN KEY (service_option_id) REFERENCES service_option(id),
                                       `name` VARCHAR(50) NOT NULL UNIQUE,
                                       `code` VARCHAR(50) NOT NULL UNIQUE,
                                       `status` ENUM('true', 'false') NOT NULL,
                                       `description` TEXT,
                                       `image` VARCHAR(50) NULL,
                                       `api` INT(11) NULL,
                                       `date_created` TIMESTAMP NOT NULL,
                                       `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;

-- vendors table
CREATE TABLE `vendor`(
                         `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `service_option_id` INT(11) NOT NULL,
                         FOREIGN KEY (service_option_id) REFERENCES service_option(id),
                         `name` VARCHAR(50) NOT NULL UNIQUE,
                         `code` VARCHAR(50) NOT NULL UNIQUE,
                         `status` ENUM('true', 'false') NOT NULL,
                         `description` TEXT,
                         `image` VARCHAR(50) NULL,
                         `api` INT(11) NULL,
                         `date_created` TIMESTAMP NOT NULL,
                         `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE utf8mb4_general_ci;