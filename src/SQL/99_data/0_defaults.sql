-- Default Page Types
REPLACE INTO pageTypes VALUES
  (NULL, 'standard', 'A standard page.'),
  (NULL, 'blog-post', 'A common or garden variety of blog post.');

-- Default Block Types
REPLACE INTO blockTypes VALUES
  (NULL, 'Text', 'A block with paragraphs of text, with an optional Title.'),
  (NULL, 'Image', 'An Image block, with Alt Text.'),
  (NULL, 'Video', 'A YouTube video.');