-- Example Sites
REPLACE INTO `sites` (`id`, `uuid`, `siteName`, `siteTitle`) VALUES
  (-1, UUID(), 'admin', 'Admin'),
  (NULL, UUID(), 'default', 'Default');
