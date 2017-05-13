CREATE TABLE view_site_statistics
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  siteId INT,
  timePeriodBegin DATETIME,
  timePeriodEnd DATETIME,
  viewsCount INT
);