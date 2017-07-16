-- Default Page Types
REPLACE INTO pageTypes (name, template, description) VALUES
  ('standard', 'CMS/standard.html.twig', 'A standard page.'),
  ('blog-post', 'CMS/blog-post.html.twig', 'A common or garden variety of blog post.'),
  ('gallery', 'CMS/gallery.html.twig','An image gallery.'),
  ('slide-show', 'CMS/side-show.html.twig','A slideshow to show off some images in sequential form.');

-- Default Block Types
REPLACE INTO blockTypes (name, description) VALUES
  ('Text', 'A block with paragraphs of text, with an optional Title.'),
  ('Image', 'An Image block, with Alt Text.'),
  ('Video', 'A YouTube video.'),
  ('Map', 'A map'),
  ('Link', 'A simple link with a label'),
  ('Sub Page', 'Another page embedded into this one.');