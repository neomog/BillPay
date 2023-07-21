CREATE TABLE `priviledge` (
                         `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `type` varchar(250) NOT NULL,
                          `reg_date` TIMESTAMP,
                           `updated_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
                        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `first_name` varchar(50) NOT NULL,
                        `last_name` varchar(50) NOT NULL,
                        `email` varchar(50) NOT NULL,
                        `password` varchar(50) NOT NULL,
                        `gender` enum('male','female') CHARACTER SET utf8 NOT NULL,
                        `mobile` varchar(50) NOT NULL,
                        `designation` varchar(50) NOT NULL,
                        `image` varchar(250) NOT NULL,
                        `type` varchar(250) NOT NULL DEFAULT 'general',
                        `status` enum('active','pending','deleted','') NOT NULL DEFAULT 'pending',
                        `authtoken` varchar(250) NOT NULL,
                        `reg_date` TIMESTAMP,
                        `updated_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;