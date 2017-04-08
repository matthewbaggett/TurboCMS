CREATE TABLE views
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  pageId INT,
  viewTime DATETIME,
  ipAddress INT,
  CONSTRAINT views_pages_id_fk FOREIGN KEY (pageId) REFERENCES pages (id)
);