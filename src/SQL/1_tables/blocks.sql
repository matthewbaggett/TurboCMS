CREATE TABLE `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `siteId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `blockTypeId` int(11) NOT NULL,
  `data` JSON NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `siteId` (`siteId`),
  KEY `pageId` (`pageId`),
  KEY `blockTypeId` (`blockTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
