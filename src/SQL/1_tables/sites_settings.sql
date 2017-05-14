CREATE TABLE sitesSettings
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `siteId` INT,
  `key` VARCHAR(255),
  `value` VARCHAR(255),
  KEY `siteId` (`siteId`),
  KEY `key` (`key`)
);