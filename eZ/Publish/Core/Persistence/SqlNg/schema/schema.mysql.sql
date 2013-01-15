-- We recreate the DB entirely -- so that we do not care about violated constraints
SET foreign_key_checks = 0;

--
-- Language table
--
DROP TABLE IF EXISTS ezcontent_language;
CREATE TABLE ezcontent_language (
    disabled int(11) NOT NULL DEFAULT '0',
    id int(11) NOT NULL DEFAULT '0',
    language_code varchar(20) NOT NULL DEFAULT '',
    name varchar(255) NOT NULL DEFAULT '',
    is_enabled int(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

--
-- Content type definitions (formerlly known as contentclass)
--
DROP TABLE IF EXISTS ezcontenttype;
CREATE TABLE ezcontenttype (
    id int(11) NOT NULL AUTO_INCREMENT,
    identifier varchar(50) NOT NULL DEFAULT '',
    initial_language_id int(11) NOT NULL DEFAULT '0',
    always_available int(11) NOT NULL DEFAULT '0',
    contentobject_name varchar(255) DEFAULT NULL,
    is_container int(11) NOT NULL DEFAULT '0',
    created int(11) NOT NULL DEFAULT '0',
    creator_id int(11) NOT NULL DEFAULT '0',
    modified int(11) NOT NULL DEFAULT '0',
    modifier_id int(11) NOT NULL DEFAULT '0',
    remote_id varchar(100) NOT NULL DEFAULT '',
    description_list LONGTEXT,
    name_list LONGTEXT,
    sort_field int(11) NOT NULL DEFAULT '1',
    sort_order int(11) NOT NULL DEFAULT '1',
    version_no int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (id, version_no),
    FOREIGN KEY (initial_language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT,
    FOREIGN KEY (creator_id) REFERENCES ezuser(id) ON DELETE RESTRICT,
    FOREIGN KEY (modifier_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_name;
CREATE TABLE ezcontenttype_name (
    contenttype_id int(11) NOT NULL DEFAULT '0',
    version_no int(11) NOT NULL DEFAULT '0',
    language_id int(11) NOT NULL DEFAULT '0',
    language_code varchar(20) NOT NULL DEFAULT '',
    name varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (contenttype_id, version_no, language_id),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_field;
CREATE TABLE ezcontenttype_field (
    id int(11) NOT NULL AUTO_INCREMENT,
    identifier varchar(50) NOT NULL DEFAULT '',
    contenttype_id int(11) NOT NULL DEFAULT '0',
    data LONGTEXT NOT NULL,
    can_translate int(11) DEFAULT '1',
    field_type_string varchar(50) NOT NULL DEFAULT '',
    is_information_collector int(11) NOT NULL DEFAULT '0',
    is_required int(11) NOT NULL DEFAULT '0',
    is_searchable int(11) NOT NULL DEFAULT '0',
    placement int(11) NOT NULL DEFAULT '0',
    data_text LONGTEXT,
    description_list LONGTEXT,
    name_list LONGTEXT NOT NULL,
    version_no int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (id, version_no),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_group;
CREATE TABLE ezcontenttype_group (
    id int(11) NOT NULL AUTO_INCREMENT,
    identifier varchar(255) DEFAULT NULL,
    created int(11) NOT NULL DEFAULT '0',
    creator_id int(11) NOT NULL DEFAULT '0',
    modified int(11) NOT NULL DEFAULT '0',
    modifier_id int(11) NOT NULL DEFAULT '0',
    name LONGTEXT,
    description LONGTEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (creator_id) REFERENCES ezuser(id) ON DELETE RESTRICT,
    FOREIGN KEY (modifier_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontenttype_group_rel;
CREATE TABLE ezcontenttype_group_rel (
    contenttype_id int(11) NOT NULL DEFAULT '0',
    version_no int(11) NOT NULL DEFAULT '0',
    group_id int(11) NOT NULL DEFAULT '0',
    group_name varchar(255) DEFAULT NULL,
    PRIMARY KEY (contenttype_id, version_no, group_id),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES ezcontenttype_group(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

--
-- Content object definitions (formerlly known as contentobject)
--
DROP TABLE IF EXISTS ezcontent;
CREATE TABLE ezcontent(
    id int(11) NOT NULL AUTO_INCREMENT,
    contenttype_id int(11) NOT NULL DEFAULT '0',
    current_version_no int(11) DEFAULT NULL,
    initial_language_id int(11) NOT NULL DEFAULT '0',
    language_mask int(11) NOT NULL DEFAULT '0',
    name varchar(255) DEFAULT NULL,
    owner_id int(11) NOT NULL DEFAULT '0', -- Keep this name, or change to modifier_id?
    modified int(11) NOT NULL DEFAULT '0',
    published int(11) NOT NULL DEFAULT '0',
    remote_id varchar(100) DEFAULT NULL,
    section_id int(11) NOT NULL DEFAULT '0',
    status int(11) DEFAULT '0',
    PRIMARY KEY (id),
    UNIQUE KEY ezcontentobject_remote_id (remote_id),
    FOREIGN KEY (contenttype_id) REFERENCES ezcontenttype(id) ON DELETE RESTRICT,
    FOREIGN KEY (initial_language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT,
    FOREIGN KEY (section_id) REFERENCES ezsection(id) ON DELETE RESTRICT,
    FOREIGN KEY (owner_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentobject_name;
CREATE TABLE ezcontentobject_name (
    content_id int(11) NOT NULL DEFAULT '0',
    content_version_no int(11) NOT NULL DEFAULT '0',
    language_id int(11) NOT NULL DEFAULT '0',
    content_translation varchar(20) NOT NULL DEFAULT '',
    name varchar(255) DEFAULT NULL,
    real_translation varchar(20) DEFAULT NULL,
    PRIMARY KEY (content_id, content_version_no, content_translation),
    FOREIGN KEY (content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentversion;
CREATE TABLE ezcontentversion (
    id int(11) NOT NULL AUTO_INCREMENT,
    content_id int(11) DEFAULT NULL,
    version_no int(11) NOT NULL DEFAULT '0',
    creator_id int(11) NOT NULL DEFAULT '0',
    created int(11) NOT NULL DEFAULT '0',
    initial_language_id int(11) NOT NULL DEFAULT '0',
    status int(11) NOT NULL DEFAULT '0',
    fields LONGTEXT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE,
    FOREIGN KEY (initial_language_id) REFERENCES ezcontent_language(id) ON DELETE RESTRICT,
    FOREIGN KEY (creator_id) REFERENCES ezuser(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentobject_relation; -- Formerlly ezcontentobject_link
CREATE TABLE ezcontentobject_relation (
    id int(11) NOT NULL AUTO_INCREMENT,
    contenttype_field_id int(11) NOT NULL DEFAULT '0',
    from_content_id int(11) NOT NULL DEFAULT '0',
    from_contentobject_version_no int(11) NOT NULL DEFAULT '0',
    to_content_id int(11) NOT NULL DEFAULT '0',
    op_code int(11) NOT NULL DEFAULT '0',
    relation_type int(11) NOT NULL DEFAULT '1',
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
    id int(11) NOT NULL AUTO_INCREMENT,
    main_id int(11) DEFAULT NULL,
    parent_id int(11) NOT NULL DEFAULT '0',
    content_id int(11) DEFAULT NULL,
    contentobject_version_no int(11) DEFAULT NULL,
    contentobject_is_published int(11) DEFAULT NULL,
    path_string varchar(255) NOT NULL DEFAULT '',
    depth int(11) NOT NULL DEFAULT '0',
    priority int(11) NOT NULL DEFAULT '0',
    remote_id varchar(100) NOT NULL DEFAULT '',
    is_hidden int(11) NOT NULL DEFAULT '0',
    is_invisible int(11) NOT NULL DEFAULT '0',
    sort_field int(11) DEFAULT '1',
    sort_order int(11) DEFAULT '1',
    PRIMARY KEY (id),
    FOREIGN KEY (main_id) REFERENCES ezcontentlocation(id) ON DELETE RESTRICT,
    FOREIGN KEY (parent_id) REFERENCES ezcontentlocation(id) ON DELETE RESTRICT,
    FOREIGN KEY (content_id) REFERENCES ezcontentobject(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezcontentobject_trash;
CREATE TABLE ezcontentobject_trash (
    id int(11) NOT NULL AUTO_INCREMENT,
    main_id int(11) DEFAULT NULL,
    parent_id int(11) NOT NULL DEFAULT '0',
    content_id int(11) DEFAULT NULL,
    contentobject_version_no int(11) DEFAULT NULL,
    contentobject_is_published int(11) DEFAULT NULL,
    path_string varchar(255) NOT NULL DEFAULT '',
    depth int(11) NOT NULL DEFAULT '0',
    priority int(11) NOT NULL DEFAULT '0',
    remote_id varchar(100) NOT NULL DEFAULT '',
    is_hidden int(11) NOT NULL DEFAULT '0',
    is_invisible int(11) NOT NULL DEFAULT '0',
    sort_field int(11) DEFAULT '1',
    sort_order int(11) DEFAULT '1',
    PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezsection`;
CREATE TABLE `ezsection` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `identifier` varchar(255) DEFAULT NULL,
    `language_code` varchar(255) DEFAULT NULL,
    `name` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Users
--
DROP TABLE IF EXISTS ezuser;
CREATE TABLE ezuser (
    id int(11) NOT NULL DEFAULT '0',
    content_id int(11) DEFAULT NULL, -- Introduced as an optional content relation
    email varchar(150) NOT NULL DEFAULT '',
    login varchar(150) NOT NULL DEFAULT '',
    password_hash varchar(50) DEFAULT NULL,
    password_hash_type int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezuser_setting;
CREATE TABLE ezuser_setting (
    user_id int(11) NOT NULL DEFAULT '0',
    is_enabled int(11) NOT NULL DEFAULT '0',
    max_login int(11) DEFAULT NULL,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `ezuser_accountkey`;
CREATE TABLE `ezuser_accountkey` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL DEFAULT '0',
    `time` int(11) NOT NULL DEFAULT '0',
    `hash_key` varchar(32) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ezuservisit`;
CREATE TABLE `ezuservisit` (
    `user_id` int(11) NOT NULL DEFAULT '0',
    `current_visit_timestamp` int(11) NOT NULL DEFAULT '0',
    `failed_login_attempts` int(11) NOT NULL DEFAULT '0',
    `last_visit_timestamp` int(11) NOT NULL DEFAULT '0',
    `login_count` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`user_id`),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ezrole;
CREATE TABLE ezrole (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezuser_role_rel;
CREATE TABLE ezuser_role_rel (
    id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11) DEFAULT NULL,
    role_id int(11) DEFAULT NULL,
    limit_identifier varchar(255) DEFAULT '',
    limit_value varchar(255) DEFAULT '',
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES ezuser(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES ezrole(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezpolicy;
CREATE TABLE ezpolicy (
    id int(11) NOT NULL AUTO_INCREMENT,
    role_id int(11) DEFAULT NULL,
    function_name varchar(255) DEFAULT NULL,
    module_name varchar(255) DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES ezrole(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezpolicy_limitation;
CREATE TABLE ezpolicy_limitation (
    id int(11) NOT NULL AUTO_INCREMENT,
    policy_id int(11) DEFAULT NULL,
    identifier varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (id),
    FOREIGN KEY (policy_id) REFERENCES ezpolicy(id) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS ezpolicy_limitation_value;
CREATE TABLE ezpolicy_limitation_value (
    id int(11) NOT NULL AUTO_INCREMENT,
    limitation_id int(11) DEFAULT NULL,
    value varchar(255) DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (limitation_id) REFERENCES ezpolicy_limitation(id) ON DELETE CASCADE
) ENGINE=InnoDB;
