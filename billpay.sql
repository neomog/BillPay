-- users table
CREATE TABLE `users`(
                        `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `first_name` VARCHAR(50) NOT NULL,
                        `last_name` VARCHAR(50) NOT NULL,
                        `user_name` VARCHAR(50) NOT NULL,
                        `email` VARCHAR(50) NOT NULL UNIQUE,
                        `password` VARCHAR(250) NOT NULL,
                        `salt` VARCHAR(250) NOT NULL,
                        `gender` ENUM('male', 'female') CHARACTER SET utf8 NOT NULL,
                        `mobile` VARCHAR(50) NOT NULL UNIQUE,
                        `image` VARCHAR(250),
                        `type` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                        `status` ENUM('active', 'pending', 'deleted', '') NOT NULL DEFAULT 'pending',
                        `auth_token` VARCHAR(250) NOT NULL,
                        `api_key` VARCHAR(250) NOT NULL,
                        `ref_id` VARCHAR(250) NOT NULL,
                        `reg_date` TIMESTAMP,
                        `date_created` TIMESTAMP NOT NULL,
                        `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

-- recharge_transactions table
CREATE TABLE recharge_transactions (
                                       `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                                       `user_id` INT(11) NOT NULL,
                                       FOREIGN KEY (user_id) REFERENCES users(id),
                                       `vendor` VARCHAR(100) NOT NULL,
                                       `request_id` VARCHAR(50) NOT NULL,
                                       `transactionId` VARCHAR(50),
                                       `vendor_response` TEXT,
                                       `status` ENUM('pending', 'success', 'failure') NOT NULL,
                                       `phone_number` VARCHAR(20),
                                       `transaction_amount` DECIMAL(10, 2) NOT NULL,
                                       `transaction_type` ENUM('airtime', 'data', 'electricity'),
                                       `payment_method` ENUM('credit_card', 'bank_transfer', 'wallet', 'cash'),
                                       `date_created` TIMESTAMP NOT NULL,
                                       `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

-- user_wallet table
CREATE TABLE `user_wallet`(
                              `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                              `user_id` INT(11) NOT NULL,
                              `wallet_balance` DECIMAL(11, 2) NOT NULL DEFAULT '0.00',
                              `date_created` TIMESTAMP NOT NULL,
                              `date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;