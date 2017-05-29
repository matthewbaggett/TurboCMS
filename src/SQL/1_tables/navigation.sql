CREATE TABLE navigation
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  siteId INT NOT NULL,
  name VARCHAR(255) NOT NULL DEFAULT 'Main Navigation',
  pageId INT NOT NULL,
  childOfPageId INT
);