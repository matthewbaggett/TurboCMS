CREATE INDEX mailServer ON mailAccount (mailServer);
CREATE INDEX siteId ON mailAccount (siteId);
CREATE INDEX userId ON mailAccount (userId);
CREATE INDEX mailAccountId ON mailMessage (mailAccountId);
CREATE UNIQUE INDEX uuid ON mailMessage (uuid);
CREATE INDEX siteId ON mailServers (siteId);
CREATE INDEX userId ON mailServers (userId);
