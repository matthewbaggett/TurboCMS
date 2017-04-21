CREATE TABLE mailMessage
(
  id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  uuid VARCHAR(36) NOT NULL,
  hash VARCHAR(64) NOT NULL,
  mailAccountId INT(11) NOT NULL,
  contactId INT(11) NOT NULL,
  subject TEXT NOT NULL,
  message BLOB NOT NULL,
  dateReceived DATETIME NOT NULL,
  dateRead DATETIME,
  `read` ENUM('Unread', 'Read') DEFAULT 'Unread' NOT NULL
);
