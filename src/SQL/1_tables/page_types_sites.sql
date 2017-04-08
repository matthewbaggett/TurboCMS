
CREATE TABLE `pageTypeSites` (
  `id` int(11) NOT NULL,
  `pageTypeId` int(11) NOT NULL,
  `siteId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `pageTypeSites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pageTypeId` (`pageTypeId`,`siteId`),
  ADD KEY `siteId` (`siteId`);

ALTER TABLE `pageTypeSites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
