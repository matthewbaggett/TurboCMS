CREATE TABLE sitesSettings
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `siteId` INT,
  `key` VARCHAR(255),
  `value` TEXT,
  KEY `siteId` (`siteId`),
  KEY `key` (`key`)
);