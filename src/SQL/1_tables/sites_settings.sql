CREATE TABLE sitesSettings
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `siteId` INT KEY,
  `key` VARCHAR(255) KEY,
  `value` VARCHAR(255)
);