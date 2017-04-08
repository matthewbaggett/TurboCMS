
CREATE TABLE `blockTypeSites` (
  `id` int(11) NOT NULL,
  `blockTypeId` int(11) NOT NULL,
  `siteId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `blockTypeSites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blockTypeId` (`blockTypeId`,`siteId`),
  ADD KEY `siteId` (`siteId`);

ALTER TABLE `blockTypeSites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
