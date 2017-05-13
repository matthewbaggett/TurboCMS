CREATE TABLE views
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  siteId INT,
  pageId INT NULL,
  viewTime DATETIME,
  CONSTRAINT views_pages_id_fk FOREIGN KEY (pageId) REFERENCES pages (id)
);