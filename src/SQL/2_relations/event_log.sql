ALTER TABLE `eventLog`
  ADD FOREIGN KEY (`siteId`)
REFERENCES `sites`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;

ALTER TABLE `eventLog`
  ADD FOREIGN KEY (`userId`)
REFERENCES `users`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;
