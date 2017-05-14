CREATE TABLE sitesDomains
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `siteId` INT,
  `domain` VARCHAR(255),
  `isTestDomain` ENUM('Yes','No') DEFAULT 'Yes',
  KEY `siteId` (`siteId`),
  KEY `domain` (`domain`),
  KEY `isTestDomain` (`isTestDomain`)
);