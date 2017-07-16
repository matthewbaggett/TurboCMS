CREATE TABLE `customerMessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `siteId` int(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `telephone` VARCHAR(255),
  `email` VARCHAR(360),
  `message` TEXT NULL,
  `dateCreated` DATETIME NOT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  `dateDeleted` DATETIME,
  `read` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `siteId` (`siteId`),
  KEY `dateCreated` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
