

ALTER TABLE `blockTags`
  ADD FOREIGN KEY (`blockId`)
REFERENCES `blocks`(`id`)
  ON DELETE RESTRICT
  ON UPDATE RESTRICT;