CREATE TABLE `usersSites` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `userId` INT NOT NULL ,
  `siteId` INT NOT NULL ,
  PRIMARY KEY (`id`),
  INDEX (`userId`, `siteId`)
) ENGINE = InnoDB;

