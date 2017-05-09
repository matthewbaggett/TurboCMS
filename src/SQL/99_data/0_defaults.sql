-- Default Page Types
REPLACE INTO pageTypes (name, template, description) VALUES
  ('standard', 'standard.twig.html', 'A standard page.'),
  ('blog-post', 'blog-post.twig.html', 'A common or garden variety of blog post.'),
  ('gallery', 'gallery.twig.html','An image gallery.'),
  ('slide-show', 'side-show.twig.html','A slideshow to show off some images in sequential form.');

-- Default Block Types
REPLACE INTO blockTypes (name, description) VALUES
  ( 'Text', 'A block with paragraphs of text, with an optional Title.'),
  ( 'Image', 'An Image block, with Alt Text.'),
  ( 'Video', 'A YouTube video.'),
  ('Sub Page', 'Another page embedded into this one.');