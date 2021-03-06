REPLACE INTO `users` (`id`, `uuid`, `email`, `firstName`, `lastName`, `password`, `deleted`) VALUES
  (NULL, UUID(), 'test@microsites.dev', 'Test', 'User', '', 'Yes');

REPLACE INTO `users` (`id`, `uuid`, `email`, `firstName`, `lastName`, `password`) VALUES
  (NULL, UUID(), 'matthew@baggett.me', 'Matthew', 'Baggett', '$2y$10$1iTYvkHrvtDGvjJgyaGwfuxyzvmgKjYIPzBXV7bbOWbbM/i7m/z1O');

REPLACE INTO `usersSites` (`userId`, `siteId`) VALUES
  ((SELECT id FROM users WHERE email='test@microsites.dev'),(SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM users WHERE email='matthew@baggett.me'),(SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM users WHERE email='matthew@baggett.me'),(SELECT id FROM sites WHERE siteName = 'admin'));