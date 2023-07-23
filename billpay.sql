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
                        `updated_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = latin1;