ALTER TABLE `mailAccount` ADD INDEX(`mailServerId`);
ALTER TABLE `mailAccount` ADD INDEX(`siteId`);
ALTER TABLE `mailAccount` ADD INDEX(`userId`);

ALTER TABLE `mailMessage` ADD INDEX(mailAccountId);
ALTER TABLE `mailMessage` ADD UNIQUE(`uuid`);
ALTER TABLE `mailMessage` ADD UNIQUE(`hash`);
ALTER TABLE `mailMessage` ADD INDEX(`read`);

ALTER TABLE `mailServers` ADD INDEX(`siteId`);
ALTER TABLE `mailServers` ADD INDEX(`userId`);

ALTER TABLE `mailContacts` ADD INDEX(`uuid`);