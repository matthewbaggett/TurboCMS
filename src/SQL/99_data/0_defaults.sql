-- Default Page Types
REPLACE INTO pageTypes (name, description) VALUES
  ('standard', 'A standard page.'),
  ('blog-post', 'A common or garden variety of blog post.'),
  ('gallery','An image gallery.'),
  ('slide-show','A slideshow to show off some images in sequential form.');

-- Default Block Types
REPLACE INTO blockTypes (name, description) VALUES
  ( 'Text', 'A block with paragraphs of text, with an optional Title.'),
  ( 'Image', 'An Image block, with Alt Text.'),
  ( 'Video', 'A YouTube video.');