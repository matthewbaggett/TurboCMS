-- Default Page Types
REPLACE INTO pageTypes (name, template, description) VALUES
  ('standard', 'CMS/standard.html.twig', 'A standard page.'),
  ('blog-post', 'CMS/blog-post.html.twig', 'A common or garden variety of blog post.'),
  ('gallery', 'CMS/gallery.html.twig','An image gallery.'),
  ('gallery-item', 'CMS/gallery-item.html.twig','An image gallery item.'),
  ('slide-show', 'CMS/side-show.html.twig','A slideshow to show off some images in sequential form.');
