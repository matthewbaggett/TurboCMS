CREATE TABLE viewSite
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `visitorId` INT NOT NULL,
  `siteId` INT NOT NULL,
  `time` DATETIME NOT NULL
);