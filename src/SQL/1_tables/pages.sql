CREATE TABLE `pages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `urlSlug` TEXT NULL,
  `siteId` INT(11) NOT NULL,
  `pageTypeId` INT(11) NOT NULL,
  `title` TEXT NOT NULL,
  `uuid` VARCHAR(36) NOT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No',
  `status` ENUM('Published','Unpublished') DEFAULT 'Unpublished',
  `publishedDate` DATETIME NULL,
  `views` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `siteId` (`siteId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
