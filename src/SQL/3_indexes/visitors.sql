ALTER TABLE `visitors` ADD INDEX(`visitorUuid`);
ALTER TABLE `visitors` ADD INDEX(`language`);
ALTER TABLE `visitors` ADD INDEX(`ipAddress`);
ALTER TABLE `visitors` ADD INDEX(`locationCity`);
ALTER TABLE `visitors` ADD INDEX `locationCoordinates` (`locationLatitude`, `locationLongitude`);