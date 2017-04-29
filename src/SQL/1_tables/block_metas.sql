CREATE TABLE `blockMetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  PRIMARY KEY (`id`),
  KEY `blockId` (`blockId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
