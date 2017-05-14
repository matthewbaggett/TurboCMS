CREATE TABLE `pageTypes` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `siteId` INT(11) NULL,
  `template` VARCHAR(255) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `pageTypes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nameAndSite` (`name`, `siteId`),
  ADD KEY `siteId` (`siteId`);

ALTER TABLE `pageTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

