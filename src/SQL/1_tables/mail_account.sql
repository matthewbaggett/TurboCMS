CREATE TABLE mailAccount
(
  id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  mailServer INT(11) NOT NULL,
  siteId INT(11) NOT NULL,
  userId INT(11),
  username TEXT,
  password TEXT,
  lastChecked DATETIME
);
