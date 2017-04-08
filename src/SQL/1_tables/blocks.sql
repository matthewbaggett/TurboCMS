CREATE TABLE `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `siteId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `data` TEXT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `siteId` (`siteId`),
  KEY `pageId` (`pageId`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
