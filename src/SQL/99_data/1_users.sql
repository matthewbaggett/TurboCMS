REPLACE INTO `users` (`id`, `uuid`, `email`, `firstName`, `lastName`, `password`) VALUES
  (NULL, UUID(), 'matthew@baggett.me', 'Matthew', 'Baggett', '$2y$10$1iTYvkHrvtDGvjJgyaGwfuxyzvmgKjYIPzBXV7bbOWbbM/i7m/z1O');

REPLACE INTO `usersSites` (`userId`, `siteId`) VALUES
  ((SELECT id FROM users where email='matthew@baggett.me'),-1);