CREATE TABLE `blockTags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blockId` int(11) NOT NULL,
  `tag` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `blockId` (`blockId`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
