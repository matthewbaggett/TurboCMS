CREATE TABLE sitesDomains
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `domain` VARCHAR(255) KEY UNIQUE,
  `siteId` INT KEY
);