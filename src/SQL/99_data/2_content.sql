-- Example Sites
REPLACE INTO `sites` (`id`, `uuid`, `siteName`, `siteTitle`) VALUES
  (-1, UUID(), 'Admin', 'admin'),
  (NULL, UUID(), 'Default', 'default');
