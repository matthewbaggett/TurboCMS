INSERT INTO pageTypeSites (pageTypeId, siteId) VALUES
  ((SELECT id FROM pageTypes WHERE `name`='standard'), (SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM pageTypes WHERE `name`='blog-post'), (SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM pageTypes WHERE `name`='gallery'), (SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM pageTypes WHERE `name`='gallery-item'), (SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM pageTypes WHERE `name`='slide-show'), (SELECT id FROM sites WHERE siteName = 'default'));

INSERT INTO blockTypeSites (blockTypeId, siteId) VALUES
  ((SELECT id FROM blockTypes WHERE `name` = 'Text'), (SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM blockTypes WHERE `name` = 'Image'), (SELECT id FROM sites WHERE siteName = 'default')),
  ((SELECT id FROM blockTypes WHERE `name` = 'Video'), (SELECT id FROM sites WHERE siteName = 'default'));