-- We recreate the DB entirely -- so that we do not care about violated constraints
SET foreign_key_checks = 0;

--
-- Language table
--
DROP TABLE IF EXISTS ezcontent_language;
CREATE TABLE ezcontent_language (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `language_code` VARCHAR(20) NOT NULL DEFAULT '',
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `is_enabled` INT(1) NOT NULL DEFAULT 0,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

--
-- Content type definitions (formerlly known as contentclass)
--
DROP TABLE IF EXISTS ezcontenttype;
CREATE TABLE ezcontenttype (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(50) NOT NULL DEFAULT '',
    `initial_language_id` INT(11) NOT NULL DEFAULT '0',
    `always_available` INT(11) NOT NULL DEFAULT '0',
    `contentobject_name` VARCHAR(255) DEFAULT NULL,
    `is_container` INT(11) NOT NULL DEFAULT '0',
    `created` INT(11) NOT NULL DEFAULT '0',
    `creator_id` INT(11) NOT NULL DEFAULT '0',
    `modified` INT(11) NOT NULL DEFAULT '0',
    `modifier_id` INT(11) NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) NOT NULL DEFAULT '',
    `description_list` LONGTEXT,
    `name_list` LONGTEXT,
    `sort_field` INT(11) NOT NULL DEFAULT '1',
    `sort_order` INT(11) NOT NULL DEFAULT '1',
    `status` INT(11) NOT NULL DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id, status),
    FOREIGN KEY (initial_language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT,
    FOREIGN KEY (creator_id) REFERENCES ezuser(id) ON DELETE RESTRICT,
    FOREIGN KEY (modifier_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_field;
CREATE TABLE ezcontenttype_field (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(50) NOT NULL DEFAULT '',
    `field_group` VARCHAR(25) NOT NULL DEFAULT '',
    `contenttype_id` INT(11) NOT NULL DEFAULT '0',
    `can_translate` INT(11) DEFAULT '1',
    `type_string` VARCHAR(50) NOT NULL DEFAULT '',
    `is_information_collector` INT(11) NOT NULL DEFAULT '0',
    `is_required` INT(11) NOT NULL DEFAULT '0',
    `is_searchable` INT(11) NOT NULL DEFAULT '0',
    `placement` INT(11) NOT NULL DEFAULT '0',
    `description_list` LONGTEXT,
    `name_list` LONGTEXT NOT NULL,
    `constraints` LONGTEXT,
    `default_value` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_group;
CREATE TABLE ezcontenttype_group (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) DEFAULT NULL,
    `created` INT(11) NOT NULL DEFAULT '0',
    `creator_id` INT(11) NOT NULL DEFAULT '0',
    `modified` INT(11) NOT NULL DEFAULT '0',
    `modifier_id` INT(11) NOT NULL DEFAULT '0',
    `name` LONGTEXT,
    `description` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (creator_id) REFERENCES ezuser(id) ON DELETE RESTRICT,
    FOREIGN KEY (modifier_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_group_rel;
CREATE TABLE ezcontenttype_group_rel (
    `contenttype_id` INT(11) NOT NULL DEFAULT '0',
    `group_id` INT(11) NOT NULL DEFAULT '0',
    `status` INT(11) NOT NULL DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (contenttype_id, group_id, status),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES ezcontenttype_group(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

--
-- Content object definitions (formerlly known as contentobject)
--
DROP TABLE IF EXISTS ezcontent;
CREATE TABLE ezcontent(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `contenttype_id` INT(11) NOT NULL DEFAULT '0',
    `current_version_no` INT(11) DEFAULT NULL,
    `initial_language_id` INT(11) NOT NULL DEFAULT '0',
    `language_mask` INT(11) NOT NULL DEFAULT '0',
    `name` VARCHAR(255) DEFAULT NULL,
    `owner_id` INT(11) NOT NULL DEFAULT '0', -- Keep this name, or change to modifier_id?
    `modified` INT(11) NOT NULL DEFAULT '0',
    `published` INT(11) NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) DEFAULT NULL,
    `section_id` INT(11) NOT NULL DEFAULT '0',
    `status` INT(11) DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY ezcontentobject_remote_id (remote_id),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE RESTRICT,
    FOREIGN KEY (initial_language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT,
    FOREIGN KEY (section_id) REFERENCES ezsection(id) ON DELETE RESTRICT,
    FOREIGN KEY (owner_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentobject_name;
CREATE TABLE ezcontentobject_name (
    `content_id` INT(11) NOT NULL DEFAULT '0',
    `content_version_no` INT(11) NOT NULL DEFAULT '0',
    `language_id` INT(11) NOT NULL DEFAULT '0',
    `content_translation` VARCHAR(20) NOT NULL DEFAULT '',
    `name` VARCHAR(255) DEFAULT NULL,
    `real_translation` VARCHAR(20) DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (content_id, content_version_no, content_translation),
    FOREIGN KEY (content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentversion;
CREATE TABLE ezcontentversion (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `content_id` INT(11) DEFAULT NULL,
    `version_no` INT(11) NOT NULL DEFAULT '0',
    `creator_id` INT(11) NOT NULL DEFAULT '0',
    `created` INT(11) NOT NULL DEFAULT '0',
    `initial_language_id` INT(11) NOT NULL DEFAULT '0',
    `status` INT(11) NOT NULL DEFAULT '0',
    `fields` LONGTEXT NOT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE,
    FOREIGN KEY (initial_language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT,
    FOREIGN KEY (creator_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentobject_relation; -- Formerlly ezcontentobject_link
CREATE TABLE ezcontentobject_relation (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `contenttype_field_id` INT(11) NOT NULL DEFAULT '0',
    `from_content_id` INT(11) NOT NULL DEFAULT '0',
    `from_contentobject_version_no` INT(11) NOT NULL DEFAULT '0',
    `to_content_id` INT(11) NOT NULL DEFAULT '0',
    `op_code` INT(11) NOT NULL DEFAULT '0',
    `relation_type` INT(11) NOT NULL DEFAULT '1',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (contenttype_field_id) REFERENCES ezcontenttype_field(id) ON DELETE RESTRICT,
    FOREIGN KEY (from_content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE,
    FOREIGN KEY (to_content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE
) ENGINE=InnoDB;

--
-- Locations (and trash) formelly known as tree
--
DROP TABLE IF EXISTS ezcontentlocation;
CREATE TABLE ezcontentlocation (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `main_id` INT(11) DEFAULT NULL,
    `parent_id` INT(11) NOT NULL DEFAULT '0',
    `content_id` INT(11) DEFAULT NULL,
    `contentobject_version_no` INT(11) DEFAULT NULL,
    `contentobject_is_published` INT(11) DEFAULT NULL,
    `path_string` VARCHAR(255) NOT NULL DEFAULT '',
    `depth` INT(11) NOT NULL DEFAULT '0',
    `priority` INT(11) NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) NOT NULL DEFAULT '',
    `is_hidden` INT(11) NOT NULL DEFAULT '0',
    `is_invisible` INT(11) NOT NULL DEFAULT '0',
    `sort_field` INT(11) DEFAULT '1',
    `sort_order` INT(11) DEFAULT '1',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (main_id) REFERENCES ezcontentlocation(id) ON DELETE RESTRICT,
    FOREIGN KEY (parent_id) REFERENCES ezcontentlocation(id) ON DELETE RESTRICT,
    FOREIGN KEY (content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentobject_trash;
CREATE TABLE ezcontentobject_trash (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `main_id` INT(11) DEFAULT NULL,
    `parent_id` INT(11) NOT NULL DEFAULT '0',
    `content_id` INT(11) DEFAULT NULL,
    `contentobject_version_no` INT(11) DEFAULT NULL,
    `contentobject_is_published` INT(11) DEFAULT NULL,
    `path_string` VARCHAR(255) NOT NULL DEFAULT '',
    `depth` INT(11) NOT NULL DEFAULT '0',
    `priority` INT(11) NOT NULL DEFAULT '0',
    `remote_id` VARCHAR(100) NOT NULL DEFAULT '',
    `is_hidden` INT(11) NOT NULL DEFAULT '0',
    `is_invisible` INT(11) NOT NULL DEFAULT '0',
    `sort_field` INT(11) DEFAULT '1',
    `sort_order` INT(11) DEFAULT '1',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezsection`;
CREATE TABLE `ezsection` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) DEFAULT NULL,
    `language_code` VARCHAR(255) DEFAULT NULL,
    `name` VARCHAR(255) DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Users
--
DROP TABLE IF EXISTS ezuser;
CREATE TABLE ezuser (
    `id` INT(11) NOT NULL DEFAULT '0',
    `content_id` INT(11) DEFAULT NULL, -- Introduced as an optional content relation
    `email` VARCHAR(150) NOT NULL DEFAULT '',
    `login` VARCHAR(150) NOT NULL DEFAULT '',
    `password_hash` VARCHAR(50) DEFAULT NULL,
    `password_hash_type` INT(11) NOT NULL DEFAULT '1',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezuser_setting;
CREATE TABLE ezuser_setting (
    `user_id` INT(11) NOT NULL DEFAULT '0',
    `is_enabled` INT(11) NOT NULL DEFAULT '0',
    `max_login` INT(11) DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezuser_accountkey`;
CREATE TABLE `ezuser_accountkey` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL DEFAULT '0',
    `time` INT(11) NOT NULL DEFAULT '0',
    `hash_key` VARCHAR(32) NOT NULL DEFAULT '',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ezuservisit`;
CREATE TABLE `ezuservisit` (
    `user_id` INT(11) NOT NULL DEFAULT '0',
    `current_visit_timestamp` INT(11) NOT NULL DEFAULT '0',
    `failed_login_attempts` INT(11) NOT NULL DEFAULT '0',
    `last_visit_timestamp` INT(11) NOT NULL DEFAULT '0',
    `login_count` INT(11) NOT NULL DEFAULT '0',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ezrole;
CREATE TABLE ezrole (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `identifier` VARCHAR(255) NOT NULL DEFAULT '',
    `name` LONGTEXT,
    `description` LONGTEXT,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezrole_content_rel;
CREATE TABLE ezrole_content_rel (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) DEFAULT NULL,
    `content_id` INT(11) DEFAULT NULL,
    `limit_identifier` VARCHAR(255) DEFAULT '',
    `limit_value` VARCHAR(255) DEFAULT '',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (content_id) REFERENCES ezcontent(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES ezrole(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezpolicy;
CREATE TABLE ezpolicy (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) DEFAULT NULL,
    `function_name` VARCHAR(255) DEFAULT NULL,
    `module_name` VARCHAR(255) DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES ezrole(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezpolicy_limitation;
CREATE TABLE ezpolicy_limitation (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `policy_id` INT(11) DEFAULT NULL,
    `identifier` VARCHAR(255) NOT NULL DEFAULT '',
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (policy_id) REFERENCES ezpolicy(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezpolicy_limitation_value;
CREATE TABLE ezpolicy_limitation_value (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `limitation_id` INT(11) DEFAULT NULL,
    `value` VARCHAR(255) DEFAULT NULL,
    `changed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (limitation_id) REFERENCES ezpolicy_limitation(id) ON DELETE CASCADE
) ENGINE=InnoDB;
