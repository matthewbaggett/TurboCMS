CREATE TABLE `sites` (
  `id` int(11) NOT NULL,
  `uuid` VARCHAR(36) NOT NULL,
  `siteName` varchar(255) NOT NULL,
  `siteTitle` varchar(255) NOT NULL,
  `deleted` ENUM('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `siteTitle` (`siteTitle`),
  ADD UNIQUE KEY `siteName` (`siteName`);

ALTER TABLE `sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;