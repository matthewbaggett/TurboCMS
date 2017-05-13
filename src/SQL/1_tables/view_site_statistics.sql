CREATE TABLE viewSiteStatistics
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  siteId INT,
  timePeriodBegin DATETIME,
  timePeriodEnd DATETIME,
  viewsCount INT
);