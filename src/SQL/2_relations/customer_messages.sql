
ALTER TABLE `customerMessages`
  ADD FOREIGN KEY (`siteId`)
REFERENCES `sites`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;