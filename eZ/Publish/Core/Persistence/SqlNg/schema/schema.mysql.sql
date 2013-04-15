SET FOREIGN_KEY_CHECKS = 0;

--
-- Language table
--
DROP TABLE IF EXISTS `ezcontent_language`;
CREATE TABLE IF NOT EXISTS `ezcontent_language` (
    `language_id` INT NOT NULL AUTO_INCREMENT,
    `language_code` VARCHAR(20) NOT NULL DEFAULT '',
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `is_enabled` INT(1) NOT NULL DEFAULT 0,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`language_id`),
    INDEX (`language_code`)
) ENGINE=InnoDB;

--
-- Users
--
DROP TABLE IF EXISTS `ezuser`;
CREATE TABLE IF NOT EXISTS `ezuser` (
    `user_id` INT NOT NULL DEFAULT '0',
    `content_id` INT DEFAULT NULL,
    `email` VARCHAR(150) NOT NULL DEFAULT '',
    `login` VARCHAR(150) NOT NULL DEFAULT '',
    `password_hash` VARCHAR(50) DEFAULT NULL,
    `password_hash_type` INT NOT NULL DEFAULT '1',
    `current_visit_timestamp` INT DEFAULT NULL,
    `last_visit_timestamp` INT DEFAULT NULL,
    `failed_login_attempts` INT DEFAULT NULL,
    `login_count` INT DEFAULT NULL,
    `is_enabled` INT DEFAULT NULL,
    `max_login` INT DEFAULT NULL,
    `hash_key` VARCHAR(32) DEFAULT NULL,
    `time_hash_key` INT DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezrole`;
CREATE TABLE IF NOT EXISTS `ezrole` (
    `role_id` INT NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) NOT NULL DEFAULT '',
    `name` LONGTEXT,
    `description` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`role_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezrole_content_rel`;
CREATE TABLE IF NOT EXISTS `ezrole_content_rel` (
    `role_id` INT NOT NULL DEFAULT '0',
    `content_id` INT NOT NULL DEFAULT '0',
    `limit_identifier` VARCHAR(255) DEFAULT '',
    `limit_value` VARCHAR(255) DEFAULT '',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`role_id`,`content_id`),
    FOREIGN KEY (`content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `ezrole` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezpolicy`;
CREATE TABLE IF NOT EXISTS `ezpolicy` (
    `policy_id` INT(10) NOT NULL AUTO_INCREMENT,
    `name` INT(10) DEFAULT NULL,
    `limitations` LONGTEXT DEFAULT NULL,
    PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB;

--
-- Content type definitions (formerlly known as contentclass)
--
DROP TABLE IF EXISTS `ezcontenttype`;
CREATE TABLE IF NOT EXISTS `ezcontenttype` (
    `type_id` INT NOT NULL AUTO_INCREMENT,
    `source_type_id` INT DEFAULT NULL,
    `identifier` VARCHAR(50) NOT NULL DEFAULT '',
    `initial_language_id` INT NOT NULL DEFAULT '0',
    `always_available` INT NOT NULL DEFAULT '0',
    `contentobject_name` VARCHAR(255) DEFAULT NULL,
    `is_container` INT NOT NULL DEFAULT '0',
    `created` INT NOT NULL DEFAULT '0',
    `creator_id` INT NOT NULL DEFAULT '0',
    `modified` INT NOT NULL DEFAULT '0',
    `modifier_id` INT NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) NOT NULL DEFAULT '',
    `description_list` LONGTEXT,
    `name_list` LONGTEXT,
    `sort_field` INT NOT NULL DEFAULT '1',
    `sort_order` INT NOT NULL DEFAULT '1',
    `status` INT NOT NULL DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`type_id`),
    UNIQUE KEY `ezcontenttype_remote_id` (`remote_id`),
    FOREIGN KEY (`source_type_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE CASCADE,
    FOREIGN KEY (`initial_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`creator_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`modifier_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontenttype_field`;
CREATE TABLE IF NOT EXISTS `ezcontenttype_field` (
    `field_id` INT NOT NULL AUTO_INCREMENT,
    `contenttype_id` INT NOT NULL DEFAULT '0',
    `identifier` VARCHAR(50) NOT NULL DEFAULT '',
    `status` INT NOT NULL DEFAULT '0',
    `field_group` VARCHAR(25) NOT NULL DEFAULT '',
    `can_translate` INT DEFAULT '1',
    `type_string` VARCHAR(50) NOT NULL DEFAULT '',
    `is_information_collector` INT NOT NULL DEFAULT '0',
    `is_required` INT NOT NULL DEFAULT '0',
    `is_searchable` INT NOT NULL DEFAULT '0',
    `placement` INT NOT NULL DEFAULT '0',
    `description_list` LONGTEXT,
    `name_list` LONGTEXT NOT NULL,
    `constraints` LONGTEXT,
    `default_value` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`field_id`),
    FOREIGN KEY (`contenttype_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontenttype_group`;
CREATE TABLE IF NOT EXISTS `ezcontenttype_group` (
    `group_id` INT NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) DEFAULT NULL,
    `created` INT NOT NULL DEFAULT '0',
    `creator_id` INT NOT NULL DEFAULT '0',
    `modified` INT NOT NULL DEFAULT '0',
    `modifier_id` INT NOT NULL DEFAULT '0',
    `name_list` LONGTEXT,
    `description_list` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`group_id`),
    FOREIGN KEY (`creator_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`modifier_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontenttype_group_rel`;
CREATE TABLE IF NOT EXISTS `ezcontenttype_group_rel` (
    `contenttype_id` INT NOT NULL DEFAULT '0',
    `group_id` INT NOT NULL DEFAULT '0',
    `status` INT NOT NULL DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`contenttype_id`,`group_id`,`status`),
    FOREIGN KEY (`group_id`) REFERENCES `ezcontenttype_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`contenttype_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

--
-- Content object definitions (formerlly known as contentobject)
--
DROP TABLE IF EXISTS `ezcontent`;
CREATE TABLE IF NOT EXISTS `ezcontent` (
    `content_id` INT NOT NULL AUTO_INCREMENT,
    `type_id` INT NOT NULL DEFAULT '0',
    `current_version_no` INT DEFAULT NULL,
    `initial_language_id` INT NOT NULL DEFAULT '0',
    `always_available` INT NOT NULL DEFAULT '0',
    `name_list` LONGTEXT,
    `owner_id` INT NOT NULL DEFAULT '0',
    `modified` INT NOT NULL DEFAULT '0',
    `published` INT NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) DEFAULT NULL,
    `section_id` INT NOT NULL DEFAULT '0',
    `status` INT DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`content_id`),
    UNIQUE KEY `ezcontentobject_remote_id` (`remote_id`),
    FOREIGN KEY (`section_id`) REFERENCES `ezsection` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`type_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`initial_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`owner_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontent_version`;
CREATE TABLE `ezcontent_version` (
    `version_id` INT NOT NULL AUTO_INCREMENT,
    `content_id` INT DEFAULT NULL,
    `version_no` INT NOT NULL DEFAULT '0',
    `modified` INT NOT NULL DEFAULT '0',
    `creator_id` INT NOT NULL DEFAULT '0',
    `created` INT NOT NULL DEFAULT '0',
    `initial_language_id` INT NOT NULL DEFAULT '0',
    `status` INT NOT NULL DEFAULT '0',
    `fields` LONGTEXT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`version_id`),
    KEY (`content_id`, `version_no`),
    FOREIGN KEY (`content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE,
    FOREIGN KEY (`initial_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`creator_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Formerlly ezcontentobject_link
DROP TABLE IF EXISTS `ezcontent_relation`;
CREATE TABLE IF NOT EXISTS `ezcontent_relation` (
    `content_id` INT(10) NOT NULL DEFAULT '0',
    `version_id` INT(10) NOT NULL DEFAULT '0',
    `to_content_id` INT(10) NOT NULL DEFAULT '0',
    `relation_type_id` INT(10) DEFAULT NULL,
    `changed` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`content_id`,`version_id`,`to_content_id`),
    FOREIGN KEY (`relation_type_id`) REFERENCES `ezrelation_types` (`relation_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`content_id`, `version_id`) REFERENCES `content_versions` (`content_id`, `version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`to_content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontent_relation_fields`;
CREATE TABLE IF NOT EXISTS `ezcontent_relation_fields` (
    `content_id` INT(10) NOT NULL DEFAULT '0',
    `version_id` INT(10) NOT NULL DEFAULT '0',
    `to_content_id` INT(10) NOT NULL DEFAULT '0',
    `content_type_filed_id` INT(10) NOT NULL DEFAULT '0',
    `relation_type_id` INT(10) DEFAULT NULL,
    `changed` INT(10) DEFAULT NULL,
    PRIMARY KEY (`content_id`,`version_id`,`to_content_id`,`content_type_filed_id`),
    FOREIGN KEY (`relation_type_id`) REFERENCES `ezrelation_types` (`relation_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`content_type_filed_id`) REFERENCES `ezcontenttype_field` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`content_id`, `version_id`, `to_content_id`) REFERENCES `ezcontent_relation` (`content_id`, `version_id`, `to_content_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezrelation_types`;
CREATE TABLE IF NOT EXISTS `ezrelation_types` (
    `relation_type_id` INT(10) NOT NULL AUTO_INCREMENT,
    `name` INT(10) DEFAULT NULL,
    PRIMARY KEY (`relation_type_id`)
) ENGINE=InnoDB;

--
-- Locations (and trash) formelly known as tree
--
DROP TABLE IF EXISTS `ezcontent_location`;
CREATE TABLE IF NOT EXISTS `ezcontent_location` (
    `location_id` INT NOT NULL AUTO_INCREMENT,
    `status` INT NOT NULL DEFAULT '0',
    `main_id` INT DEFAULT NULL,
    `parent_id` INT DEFAULT NULL,
    `content_id` INT DEFAULT NULL,
    `content_version_no` INT DEFAULT NULL,
    `path_string` VARCHAR(255) NOT NULL DEFAULT '',
    `depth` INT NOT NULL DEFAULT '0',
    `priority` INT NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) NOT NULL DEFAULT '',
    `is_hidden` INT NOT NULL DEFAULT '0',
    `is_invisible` INT NOT NULL DEFAULT '0',
    `sort_field` INT DEFAULT '1',
    `sort_order` INT DEFAULT '1',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`location_id`,`status`),
    UNIQUE KEY `ezcontent_location_remote_id` (`remote_id`),
    FOREIGN KEY (`main_id`) REFERENCES `ezcontent_location` (`location_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`parent_id`) REFERENCES `ezcontent_location` (`location_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezsection`;
CREATE TABLE IF NOT EXISTS `ezsection` (
    `section_id` INT NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) DEFAULT NULL,
    `language_code` VARCHAR(255) DEFAULT NULL,
    `name` VARCHAR(255) DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`section_id`)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
