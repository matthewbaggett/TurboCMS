CREATE TABLE visitors
(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `visitorUuid` VARCHAR(36),
  `language` VARCHAR(32),
  `browser` JSON,
  `ipAddress` VARCHAR(16),
  `locationCity` VARCHAR(32) NULL,
  `locationCountry` VARCHAR(32) NULL,
  `locationLatitude` DECIMAL(18,12) NULL,
  `locationLongitude` DECIMAL(18,12) NULL
);