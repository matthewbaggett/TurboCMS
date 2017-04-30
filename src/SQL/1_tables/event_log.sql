CREATE TABLE `eventLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `siteId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `message` TEXT NULL,
  `data` TEXT NULL,
  `dateCreated` DATETIME NOT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `siteId` (`siteId`),
  KEY `userId` (`userId`),
  KEY `dateCreated` (`dateCreated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
