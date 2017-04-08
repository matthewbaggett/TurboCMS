CREATE TABLE `pages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(36) NOT NULL,
  `siteId` INT(11) NOT NULL,
  `pageTypeId` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `urlSlug` TEXT NOT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  `status` ENUM('Published','Unpublished') DEFAULT 'Unpublished',
  `publishedDate` DATETIME NULL,
  `views` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `siteId` (`siteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
