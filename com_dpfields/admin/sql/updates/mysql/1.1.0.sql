ALTER TABLE `#__dpfields_fields` CHANGE `catid` `assigned_cat_ids` VARCHAR( 255 ) NOT NULL DEFAULT  '';

ALTER TABLE `#__dpfields_fields` ADD `catid` INT( 10 ) NOT NULL DEFAULT '0' AFTER `context`;