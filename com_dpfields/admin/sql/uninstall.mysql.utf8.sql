DROP TABLE IF EXISTS `#__dpfields_content_types`;
DROP TABLE IF EXISTS `#__dpfields_entities`;

DELETE FROM `#__content_types` WHERE `type_alias` like 'com_dpfields%';