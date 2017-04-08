CREATE TABLE `blockTypes` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `blockTypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `blockTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

