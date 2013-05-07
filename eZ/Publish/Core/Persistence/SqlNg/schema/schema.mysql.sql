SET FOREIGN_KEY_CHECKS = 0;

--
-- Language table
--
DROP TABLE IF EXISTS `ezcontent_language`;
CREATE TABLE IF NOT EXISTS `ezcontent_language` (
    `language_id` INT NOT NULL AUTO_INCREMENT,
    `language_code` VARCHAR(20) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `is_enabled` INT(1) NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`language_id`),
    INDEX (`language_code`)
) ENGINE=InnoDB;

--
-- Users
--
DROP TABLE IF EXISTS `ezuser`;
CREATE TABLE IF NOT EXISTS `ezuser` (
    `user_id` INT NOT NULL,
    `content_id` INT DEFAULT NULL,
    `email` VARCHAR(150) NOT NULL,
    `login` VARCHAR(150) NOT NULL,
    `password_hash` VARCHAR(50) DEFAULT NULL,
    `password_hash_type` INT NOT NULL,
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
    `identifier` VARCHAR(255) NOT NULL,
    `name` LONGTEXT,
    `description` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`role_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezrole_content_rel`;
CREATE TABLE IF NOT EXISTS `ezrole_content_rel` (
    `role_id` INT NOT NULL,
    `content_id` INT NOT NULL,
    `limit_identifier` VARCHAR(255) DEFAULT '',
    `limit_value` VARCHAR(255) DEFAULT '',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`role_id`, `content_id`),
    FOREIGN KEY (`content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `ezrole` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezrole_policy`;
CREATE TABLE IF NOT EXISTS `ezrole_policy` (
    `policy_id` INT(10) NOT NULL AUTO_INCREMENT,
    `role_id` INT NOT NULL,
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
    `identifier` VARCHAR(50) NOT NULL,
    `initial_language_id` INT NOT NULL,
    `always_available` INT NOT NULL,
    `contentobject_name` VARCHAR(255) DEFAULT NULL,
    `is_container` INT NOT NULL,
    `created` INT NOT NULL,
    `creator_id` INT NOT NULL,
    `modified` INT NOT NULL,
    `modifier_id` INT NOT NULL,
    `remote_id` VARCHAR(100) NOT NULL,
    `description_list` LONGTEXT,
    `name_list` LONGTEXT,
    `sort_field` INT NOT NULL DEFAULT '1',
    `sort_order` INT NOT NULL DEFAULT '1',
    `status` INT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`type_id`),
    UNIQUE KEY `ezcontenttype_remote_id` (`remote_id`, `status`),
    UNIQUE KEY `ezcontenttype_identifier` (`identifier`, `status`),
    FOREIGN KEY (`source_type_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE SET NULL,
    FOREIGN KEY (`initial_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`creator_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`modifier_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontenttype_field`;
CREATE TABLE IF NOT EXISTS `ezcontenttype_field` (
    `field_id` INT NOT NULL AUTO_INCREMENT,
    `type_id` INT NOT NULL,
    `identifier` VARCHAR(50) NOT NULL,
    `status` INT NOT NULL,
    `field_group` VARCHAR(25) NOT NULL,
    `can_translate` INT DEFAULT '1',
    `type_string` VARCHAR(50) NOT NULL,
    `is_information_collector` INT NOT NULL,
    `is_required` INT NOT NULL,
    `is_searchable` INT NOT NULL,
    `placement` INT NOT NULL,
    `description_list` LONGTEXT,
    `name_list` LONGTEXT NOT NULL,
    `constraints` LONGTEXT,
    `default_value` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`field_id`, `type_id`),
    FOREIGN KEY (`type_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontenttype_group`;
CREATE TABLE IF NOT EXISTS `ezcontenttype_group` (
    `group_id` INT NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) DEFAULT NULL,
    `created` INT NOT NULL,
    `creator_id` INT NOT NULL,
    `modified` INT NOT NULL,
    `modifier_id` INT NOT NULL,
    `name_list` LONGTEXT,
    `description_list` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`group_id`),
    FOREIGN KEY (`creator_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`modifier_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontenttype_group_rel`;
CREATE TABLE IF NOT EXISTS `ezcontenttype_group_rel` (
    `type_id` INT NOT NULL,
    `group_id` INT NOT NULL,
    `status` INT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`type_id`, `group_id`, `status`),
    FOREIGN KEY (`group_id`) REFERENCES `ezcontenttype_group` (`group_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`type_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

--
-- Content object definitions (formerlly known as contentobject)
--
DROP TABLE IF EXISTS `ezcontent`;
CREATE TABLE IF NOT EXISTS `ezcontent` (
    `content_id` INT NOT NULL AUTO_INCREMENT,
    `type_id` INT NOT NULL,
    `current_version_no` INT DEFAULT NULL,
    `initial_language_id` INT NOT NULL,
    `always_available` INT NOT NULL,
    `name_list` LONGTEXT,
    `owner_id` INT NOT NULL,
    `modified` INT NOT NULL,
    `published` INT NOT NULL,
    `remote_id` VARCHAR(100) DEFAULT NULL,
    `section_id` INT NOT NULL,
    `status` INT DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`content_id`),
    UNIQUE KEY `ezcontentobject_remote_id` (`remote_id`),
    FOREIGN KEY (`section_id`) REFERENCES `ezsection` (`section_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
    FOREIGN KEY (`type_id`) REFERENCES `ezcontenttype` (`type_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`initial_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT,
    FOREIGN KEY (`owner_id`) REFERENCES `ezuser` (`user_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontent_version`;
CREATE TABLE `ezcontent_version` (
    `version_id` INT NOT NULL AUTO_INCREMENT,
    `content_id` INT DEFAULT NULL,
    `version_no` INT NOT NULL,
    `modified` INT NOT NULL,
    `creator_id` INT NOT NULL,
    `created` INT NOT NULL,
    `initial_language_id` INT NOT NULL,
    `status` INT NOT NULL,
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
    `content_id` INT NOT NULL,
    `version_no` INT NOT NULL,
    `to_content_id` INT NOT NULL,
    `content_type_field_id` INT DEFAULT NULL,
    `relation_type` INT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY (`content_id`, `version_no`, `to_content_id`),
    FOREIGN KEY (`content_id`, `version_no`) REFERENCES `ezcontent_version` (`content_id`, `version_no`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`content_type_field_id`) REFERENCES `ezcontenttype_field` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`to_content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Locations (and trash) formelly known as tree
--
DROP TABLE IF EXISTS `ezcontent_location`;
CREATE TABLE IF NOT EXISTS `ezcontent_location` (
    `location_id` INT NOT NULL AUTO_INCREMENT,
    `status` INT NOT NULL,
    `main_id` INT DEFAULT NULL,
    `parent_id` INT DEFAULT NULL,
    `content_id` INT DEFAULT NULL,
    `content_version_no` INT DEFAULT NULL,
    `path_string` VARCHAR(255) NOT NULL,
    `depth` INT NOT NULL,
    `priority` INT NOT NULL,
    `remote_id` VARCHAR(100) NOT NULL,
    `is_hidden` INT NOT NULL,
    `is_invisible` INT NOT NULL,
    `sort_field` INT DEFAULT '1',
    `sort_order` INT DEFAULT '1',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`location_id`, `status`),
    KEY (`path_string`, `status`),
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

--
-- Object states
--

DROP TABLE IF EXISTS `ezcontent_state`;
CREATE TABLE `ezcontent_state` (
    `state_id` INT NOT NULL AUTO_INCREMENT,
    `default_language_id` INT NOT NULL,
    `state_group_id` INT NOT NULL,
    `identifier` VARCHAR(45) NOT NULL,
    `priority` INT NOT NULL,
    `name` LONGTEXT NOT NULL,
    `description` LONGTEXT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`state_id`),
    UNIQUE KEY `ezcontent_state_identifier` (`state_group_id`, `identifier`),
    KEY `ezcontent_state_priority` (`priority`),
    FOREIGN KEY (`state_group_id`) REFERENCES `ezcontent_state_group` (`state_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`default_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontent_state_group`;
CREATE TABLE `ezcontent_state_group` (
    `state_group_id` INT NOT NULL AUTO_INCREMENT,
    `default_language_id` INT NOT NULL,
    `identifier` VARCHAR(45) NOT NULL,
    `name` LONGTEXT NOT NULL,
    `description` LONGTEXT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`state_group_id`),
    UNIQUE KEY `ezcontent_state_group_identifier` (`identifier`),
    FOREIGN KEY (`default_language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezcontent_state_link`;
CREATE TABLE `ezcontent_state_link` (
    `content_id` INT NOT NULL,
    `state_group_id` INT NOT NULL,
    `state_id` INT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`content_id`, `state_group_id`, `state_id`),
    UNIQUE KEY (`content_id`, `state_group_id`),
    FOREIGN KEY (`content_id`) REFERENCES `ezcontent` (`content_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`state_id`) REFERENCES `ezcontent_state` (`state_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Url Aliases and wildcards
--

DROP TABLE IF EXISTS `ezurl_wildcard`;
CREATE TABLE `ezurl_wildcard` (
    `wildcard_id` INT NOT NULL AUTO_INCREMENT,
    `source` TEXT NOT NULL,
    `destination` TEXT NOT NULL,
    `forward` INT NOT NULL,
    PRIMARY KEY (`wildcard_id`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezurl_alias`;
CREATE TABLE `ezurl_alias` (
    `alias_id` INT NOT NULL AUTO_INCREMENT,
    `location_id` INT NOT NULL,
    `path` TEXT NOT NULL,
    `language_id` INT NOT NULL,
    -- `language_list varchar NOT NULL,
    PRIMARY KEY (`alias_id`),
    FOREIGN KEY (`language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezurl_alias_target`;
CREATE TABLE `ezurl_alias_target` (
    `path_hash` BINARY(32) NOT NULL,
    `path` TEXT NOT NULL,
    `target` TEXT NOT NULL,
    `location_id` INT NOT NULL,
    `type` INT NOT NULL,
    `language_id` INT NOT NULL,
    PRIMARY KEY (`path_hash`),
    FOREIGN KEY (`language_id`) REFERENCES `ezcontent_language` (`language_id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
