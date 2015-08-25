DROP TABLE IF EXISTS `#__dpfields_fields`;
DROP TABLE IF EXISTS `#__dpfields_fields_values`;

DELETE FROM `#__content_types` WHERE `type_alias` like 'com_dpfields%';