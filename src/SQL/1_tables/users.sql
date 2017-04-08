CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `email` varchar(320) NOT NULL,
  `firstName` VARCHAR(255) NULL,
  `lastName` VARCHAR(255) NOT NULL,
  `password` text NOT NULL,
  `lastLogin` DATETIME NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
