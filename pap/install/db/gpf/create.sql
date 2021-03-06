-- -----------------------------------------------------
-- Table `qu_g_authusers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_authusers` (
  `authid` CHAR(8) NOT NULL ,
  `username` VARCHAR(60) NOT NULL ,
  `rpassword` VARCHAR(60) NOT NULL ,
  `salt` CHAR(64) NULL DEFAULT NULL ,
  `firstname` VARCHAR(100) NULL DEFAULT NULL ,
  `lastname` VARCHAR(100) NULL DEFAULT NULL ,
  `authtoken` VARCHAR(100) NULL DEFAULT NULL ,
  `notificationemail` VARCHAR(80) NULL DEFAULT NULL ,
  `ip` VARCHAR(40) NULL DEFAULT NULL ,
  `openid_user_id` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`authid`)  ,
  UNIQUE INDEX `UC_qu_g_authusers_username` (`username` ASC)  ,
  INDEX `IDX_pa_affiliates_2` (`username` ASC, `rpassword` ASC)  ,
  INDEX `IDX_qu_g_authusers_firstname` (`firstname` ASC)  ,
  INDEX `IDX_qu_g_authusers_lastname` (`lastname` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_accounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_accounts` (
  `accountid` CHAR(8) NOT NULL ,
  `name` VARCHAR(80) NOT NULL COMMENT 'name of the account',
  `rstatus` CHAR(1) NOT NULL DEFAULT '0' COMMENT 'status of the account - P pending,A active,D declined',
  `application` VARCHAR(40) NULL DEFAULT NULL ,
  `email` VARCHAR(255) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `data3` VARCHAR(255) NULL DEFAULT NULL ,
  `data4` VARCHAR(255) NULL DEFAULT NULL ,
  `data5` VARCHAR(255) NULL DEFAULT NULL ,
  `data6` VARCHAR(255) NULL DEFAULT NULL ,
  `data7` VARCHAR(255) NULL DEFAULT NULL ,
  `data8` VARCHAR(255) NULL DEFAULT NULL ,
  `data9` VARCHAR(255) NULL DEFAULT NULL ,
  `data10` VARCHAR(255) NULL DEFAULT NULL ,
  `data11` VARCHAR(255) NULL DEFAULT NULL ,
  `data12` VARCHAR(255) NULL DEFAULT NULL ,
  `data13` VARCHAR(255) NULL DEFAULT NULL ,
  `data14` VARCHAR(255) NULL DEFAULT NULL ,
  `data15` VARCHAR(255) NULL DEFAULT NULL ,
  `data16` VARCHAR(255) NULL DEFAULT NULL ,
  `data17` VARCHAR(255) NULL DEFAULT NULL ,
  `data18` VARCHAR(255) NULL DEFAULT NULL ,
  `data19` VARCHAR(255) NULL DEFAULT NULL ,
  `data20` VARCHAR(255) NULL DEFAULT NULL ,
  `data21` VARCHAR(255) NULL DEFAULT NULL ,
  `data22` VARCHAR(255) NULL DEFAULT NULL ,
  `data23` VARCHAR(255) NULL DEFAULT NULL ,
  `data24` VARCHAR(255) NULL DEFAULT NULL ,
  `data25` VARCHAR(255) NULL DEFAULT NULL ,
  `agreement` TEXT NULL DEFAULT NULL ,
  `accountnote` TEXT NULL DEFAULT NULL ,
  `systemnote` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`accountid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_roles` (
  `roleid` CHAR(8) NOT NULL ,
  `name` VARCHAR(100) NOT NULL ,
  `roletype` VARCHAR(40) NULL DEFAULT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`roleid`)  ,
  INDEX `IDX_qu_g_roles_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_rolesprivileges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_rolesprivileges` (
  `roleprivilegeid` INT NOT NULL AUTO_INCREMENT ,
  `roleid` CHAR(8) NOT NULL ,
  `object` VARCHAR(40) NULL DEFAULT NULL ,
  `privilege` VARCHAR(40) NULL DEFAULT NULL ,
  PRIMARY KEY (`roleprivilegeid`)  ,
  UNIQUE INDEX `TUC_qu_g_rolesprivileges_1` (`privilege` ASC, `object` ASC, `roleid` ASC)  ,
  INDEX `IDX_qu_g_rolesprivileges_1` (`roleid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_settings` (
  `settingid` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `value` MEDIUMTEXT NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`settingid`)  ,
  UNIQUE INDEX `IDX_qu_g_settings_1` (`name` ASC, `accountid` ASC)  ,
  INDEX `IDX_qu_g_settings_2` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_users` (
  `accountuserid` CHAR(8) NOT NULL ,
  `authid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `roleid` CHAR(8) NOT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'A' ,
  `lastlogin` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00' ,
  `loginscount` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`accountuserid`)  ,
  INDEX `IDX_qu_g_users_1` (`authid` ASC)  ,
  INDEX `IDX_qu_g_users_2` (`accountid` ASC)  ,
  INDEX `IDX_qu_g_users_3` (`roleid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_filters`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_filters` (
  `filterid` VARCHAR(8) NOT NULL ,
  `name` VARCHAR(100) NULL DEFAULT NULL ,
  `filtertype` VARCHAR(100) NULL DEFAULT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `preset` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`filterid`)  ,
  INDEX `IDX_qu_g_filters_1` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_filter_conditions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_filter_conditions` (
  `fieldid` VARCHAR(50) NOT NULL ,
  `filterid` VARCHAR(8) NOT NULL ,
  `sectioncode` VARCHAR(50) NOT NULL ,
  `code` VARCHAR(50) NULL DEFAULT NULL ,
  `operator` VARCHAR(6) NULL DEFAULT NULL ,
  `value` VARCHAR(250) NULL DEFAULT NULL ,
  PRIMARY KEY (`fieldid`, `filterid`, `sectioncode`)  ,
  INDEX `IDX_qu_g_filter_conditions_1` (`filterid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_userattributes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_userattributes` (
  `attributeid` CHAR(8) NOT NULL ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`attributeid`)  ,
  UNIQUE INDEX `UN_qu_g_userattributes_1` (`accountuserid` ASC, `name` ASC)  ,
  INDEX `IDX_qu_g_userattributes_1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_views`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_views` (
  `viewid` CHAR(8) NOT NULL ,
  `viewtype` VARCHAR(100) NULL DEFAULT NULL ,
  `name` VARCHAR(100) NULL DEFAULT NULL ,
  `rowsperpage` INT NULL DEFAULT NULL ,
  `accountuserid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`viewid`, `accountuserid`)  ,
  INDEX `IDX_qu_g_views_1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_view_columns`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_view_columns` (
  `name` VARCHAR(50) NOT NULL ,
  `viewid` CHAR(8) NOT NULL ,
  `sorted` CHAR(1) NULL DEFAULT NULL COMMENT 'A - ascending D - descending null - none',
  `width` VARCHAR(6) NULL DEFAULT NULL ,
  `rorder` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`name`, `viewid`)  ,
  INDEX `IDX_qu_g_view_columns_1` (`viewid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_files` (
  `fileid` CHAR(32) NOT NULL ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  `filename` VARCHAR(255) NOT NULL ,
  `filesize` INT UNSIGNED NULL DEFAULT NULL ,
  `filetype` VARCHAR(255) NULL DEFAULT NULL ,
  `downloads` INT UNSIGNED NULL DEFAULT NULL ,
  `referenced` INT UNSIGNED NULL DEFAULT 0 ,
  `path` TEXT NULL DEFAULT NULL ,
  `is_public` CHAR(1) NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`fileid`)  ,
  INDEX `IDX_qu_g_files_1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_filecontents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_filecontents` (
  `fileid` CHAR(32) NOT NULL ,
  `contentid` MEDIUMINT UNSIGNED NOT NULL ,
  `content` LONGBLOB NULL DEFAULT NULL ,
  PRIMARY KEY (`fileid`, `contentid`)  ,
  INDEX `IDX_qu_g_filecontents_1` (`fileid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_activeviews`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_activeviews` (
  `accountuserid` CHAR(8) NOT NULL ,
  `viewtype` VARCHAR(100) NOT NULL ,
  `activeviewid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`accountuserid`, `viewtype`)  ,
  INDEX `IDX_qu_g_activeviews_1` (`accountuserid` ASC)  ,
  INDEX `IDX_qu_g_activeviews_2` (`activeviewid` ASC, `accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_logs` (
  `logid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `groupid` VARCHAR(32) NOT NULL DEFAULT '' ,
  `level` SMALLINT UNSIGNED NOT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `filename` VARCHAR(255) NULL DEFAULT NULL ,
  `message` TEXT NULL DEFAULT NULL ,
  `line` INT UNSIGNED NULL DEFAULT NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`logid`)  ,
  INDEX `qu_g_users_qu_g_logs_idx` (`accountuserid` ASC)  ,
  INDEX `IDX_qu_g_logs_1` (`created` ASC)  ,
  INDEX `IDX_qu_g_level_1` (`level` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_currencies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_currencies` (
  `currencyid` CHAR(8) NOT NULL ,
  `name` VARCHAR(40) NOT NULL ,
  `symbol` VARCHAR(40) NOT NULL ,
  `isdefault` TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
  `cprecision` TINYINT NOT NULL DEFAULT 2 ,
  `wheredisplay` TINYINT UNSIGNED NULL DEFAULT 1 ,
  `exchrate` FLOAT UNSIGNED NOT NULL DEFAULT 1 ,
  `accountid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`currencyid`)  ,
  INDEX `IDX_qu_g_currencies_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_mail_templates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_mail_templates` (
  `templateid` VARCHAR(8) NOT NULL ,
  `classname` VARCHAR(255) NOT NULL ,
  `templatename` VARCHAR(255) NOT NULL ,
  `subject` TEXT NULL DEFAULT NULL ,
  `body_html` LONGTEXT NULL DEFAULT NULL ,
  `body_text` LONGTEXT NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `is_custom` CHAR(1) NOT NULL DEFAULT 'N' ,
  `created` DATETIME NULL DEFAULT NULL ,
  `userid` VARCHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`templateid`)  ,
  INDEX `IDX_qu_g_mail_templates_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_mail_template_attachments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_mail_template_attachments` (
  `fileid` CHAR(32) NOT NULL ,
  `templateid` VARCHAR(8) NOT NULL ,
  `is_included_image` CHAR(1) NOT NULL ,
  PRIMARY KEY (`fileid`, `templateid`)  ,
  INDEX `IDX_qu_g_mail_template_attachments_1` (`fileid` ASC)  ,
  INDEX `IDX_qu_g_mail_template_attachments_2` (`templateid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_mail_accounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_mail_accounts` (
  `mailaccountid` VARCHAR(8) NOT NULL ,
  `account_name` VARCHAR(255) NOT NULL ,
  `account_email` VARCHAR(255) NOT NULL ,
  `from_name` VARCHAR(255) NULL DEFAULT NULL ,
  `pop3_server` VARCHAR(255) NULL DEFAULT NULL ,
  `pop3_port` SMALLINT UNSIGNED NULL DEFAULT NULL ,
  `pop3_ssl` CHAR(1) NOT NULL DEFAULT 'N' ,
  `pop3_username` VARCHAR(255) NULL DEFAULT NULL ,
  `pop3_password` VARCHAR(255) NULL DEFAULT NULL ,
  `use_smtp` CHAR(1) NULL DEFAULT 'N' ,
  `smtp_server` VARCHAR(255) NULL DEFAULT NULL ,
  `smtp_port` SMALLINT UNSIGNED NULL DEFAULT NULL ,
  `smtp_ssl` CHAR(1) NOT NULL DEFAULT 'N' ,
  `smtp_auth` CHAR(1) NOT NULL DEFAULT 'Y' ,
  `smtp_username` VARCHAR(255) NULL DEFAULT NULL ,
  `smtp_password` VARCHAR(255) NULL DEFAULT NULL ,
  `delete_mails` CHAR(1) NOT NULL DEFAULT 'N' ,
  `last_unique_id` VARCHAR(255) NULL DEFAULT NULL ,
  `is_default` CHAR(1) NOT NULL DEFAULT 'N' ,
  `last_mail_datetime` DATETIME NULL DEFAULT NULL ,
  `last_processing` DATETIME NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `smtp_auth_method` VARCHAR(16) NULL DEFAULT NULL ,
  PRIMARY KEY (`mailaccountid`)  ,
  INDEX `IDX_qu_g_mail_accounts_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_windows`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_windows` (
  `content` VARCHAR(255) NOT NULL ,
  `accountuserid` CHAR(8) NOT NULL ,
  `positiontop` INT NULL DEFAULT NULL ,
  `positionleft` INT NULL DEFAULT NULL ,
  `width` INT NULL DEFAULT NULL ,
  `height` INT NULL DEFAULT NULL ,
  `zindex` INT NULL DEFAULT NULL ,
  `closed` CHAR(1) NULL DEFAULT NULL ,
  `minimized` CHAR(1) NULL DEFAULT NULL ,
  `autorefreshtime` INT NOT NULL DEFAULT -1 COMMENT '-1 not initialized 0 disabled > 0 enabled in miliseconds',
  PRIMARY KEY (`content`, `accountuserid`)  ,
  INDEX `IDX_qu_g_windows_1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_countries`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_countries` (
  `countryid` VARCHAR(8) NOT NULL ,
  `countrycode` CHAR(8) NOT NULL ,
  `country` VARCHAR(80) NULL DEFAULT NULL ,
  `status` VARCHAR(1) NOT NULL DEFAULT 'E' ,
  `rorder` INT NOT NULL DEFAULT 0 ,
  `accountid` VARCHAR(8) NOT NULL ,
  PRIMARY KEY (`countryid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_sections`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_sections` (
  `sectionid` VARCHAR(8) NOT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `rtype` VARCHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`sectionid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_formfields`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_formfields` (
  `formfieldid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `sectionid` VARCHAR(8) NULL DEFAULT NULL ,
  `formid` VARCHAR(40) NOT NULL COMMENT 'id of a form where the field should be diplayed e.g.: affiliate = affiliate form payout_option_123456 = fields in payout option 123456',
  `code` VARCHAR(40) NOT NULL ,
  `name` VARCHAR(100) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL COMMENT 'T-text, D-text with default, A-textarea, P-password, N-number, B-checkbox, G-checkbox group, L-lisbox, R-radio, C - country listbox',
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'M-mandatory, O-optional, H-hidden (visible only to some user type or group of users), D-disabled, R-readonly, P-not in signup, S-only signup',
  `availablevalues` TEXT NULL DEFAULT NULL COMMENT 'list of available values (used mainly for listbox, checkbox group)',
  `rorder` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`formfieldid`)  ,
  INDEX `IDX_qu_g_formfields_1` (`accountid` ASC)  ,
  INDEX `IDX_qu_g_formfields_2` (`sectionid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_mails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_mails` (
  `mailid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `hdr_message_id` VARCHAR(255) NULL DEFAULT NULL ,
  `unique_message_id` VARCHAR(255) NOT NULL ,
  `subject` TEXT NULL DEFAULT NULL ,
  `headers` TEXT NULL DEFAULT NULL ,
  `body_text` LONGTEXT NULL DEFAULT NULL ,
  `body_html` LONGTEXT NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  `delivered` DATETIME NULL DEFAULT NULL ,
  `from_mail` TEXT NULL DEFAULT NULL ,
  `to_recipients` TEXT NULL DEFAULT NULL ,
  `cc_recipients` TEXT NULL DEFAULT NULL ,
  `bcc_recipients` TEXT NULL DEFAULT NULL ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  `reply_to` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`mailid`)  ,
  INDEX `IDX_qu_g_mails_1` (`accountuserid` ASC)  ,
  INDEX `IDX_qu_g_mails_2` (`created` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_mail_outbox`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_mail_outbox` (
  `outboxid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `mailaccountid` VARCHAR(8) NULL DEFAULT NULL ,
  `scheduled_at` DATETIME NOT NULL ,
  `status` CHAR(1) NOT NULL COMMENT 's-sending, p-pending, r-sent',
  `last_retry` DATETIME NULL DEFAULT NULL ,
  `retry_nr` SMALLINT UNSIGNED NULL DEFAULT NULL ,
  `error_msg` TEXT NULL DEFAULT NULL ,
  `mailid` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`outboxid`)  ,
  INDEX `IDX_qu_g_mail_outbox_1` (`mailaccountid` ASC)  ,
  INDEX `IDX_qu_g_mail_outbox_2` (`mailid` ASC)  ,
  INDEX `IDX_qu_g_mail_outbox_scheduled_at` (`scheduled_at` ASC)  ,
  INDEX `IDX_qu_g_mail_status` (`status` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_mail_attachments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_mail_attachments` (
  `fileid` CHAR(32) NOT NULL ,
  `mailid` INT UNSIGNED NOT NULL ,
  `is_included_image` CHAR(1) NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`fileid`, `mailid`)  ,
  INDEX `IDX_qu_g_mail_attachments_1` (`fileid` ASC)  ,
  INDEX `IDX_qu_g_mail_attachments_2` (`mailid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_words`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_words` (
  `wordid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `wordtext` VARCHAR(255) NOT NULL ,
  `wordlength` MEDIUMINT UNSIGNED NULL DEFAULT NULL ,
  `w1` CHAR(2) NULL DEFAULT NULL ,
  `w2` CHAR(2) NULL DEFAULT NULL ,
  `w3` CHAR(2) NULL DEFAULT NULL ,
  `w4` CHAR(2) NULL DEFAULT NULL ,
  `w5` CHAR(2) NULL DEFAULT NULL ,
  `w6` CHAR(2) NULL DEFAULT NULL ,
  `w7` CHAR(2) NULL DEFAULT NULL ,
  `w8` CHAR(2) NULL DEFAULT NULL ,
  `w9` CHAR(2) NULL DEFAULT NULL ,
  `w10` CHAR(2) NULL DEFAULT NULL ,
  `w11` CHAR(2) NULL DEFAULT NULL ,
  `w12` CHAR(2) NULL DEFAULT NULL ,
  `w13` CHAR(2) NULL DEFAULT NULL ,
  `w14` CHAR(2) NULL DEFAULT NULL ,
  `w15` CHAR(2) NULL DEFAULT NULL ,
  `w16` CHAR(2) NULL DEFAULT NULL ,
  PRIMARY KEY (`wordid`)  ,
  UNIQUE INDEX `IDX_Entity_11` (`wordtext` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_gadgets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_gadgets` (
  `gadgetid` CHAR(8) NOT NULL ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NOT NULL COMMENT 'U - UWA gadget G - Google GADGET',
  `name` VARCHAR(80) NOT NULL ,
  `url` VARCHAR(250) NOT NULL ,
  `positiontype` CHAR(1) NOT NULL COMMENT 'D - desktop S - sidebar H - hidden',
  `positiontop` INT NULL DEFAULT NULL ,
  `positionleft` INT NULL DEFAULT NULL ,
  `width` INT NULL DEFAULT NULL ,
  `height` INT NULL DEFAULT NULL ,
  `autorefreshtime` INT NOT NULL DEFAULT -1 COMMENT '-1 not initialized 0 disabled > 0 enabled in miliseconds',
  PRIMARY KEY (`gadgetid`)  ,
  INDEX `IDX_qu_g_gadgets_1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_gadgetproperties`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_gadgetproperties` (
  `gadgetpropertyid` CHAR(8) NOT NULL ,
  `gadgetid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`gadgetpropertyid`)  ,
  INDEX `IDX_qu_g_gadgetproperties_1` (`gadgetid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_passwd_requests`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_passwd_requests` (
  `requestid` CHAR(8) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `authid` CHAR(8) NULL DEFAULT NULL ,
  `status` CHAR(1) NOT NULL DEFAULT 'p' ,
  PRIMARY KEY (`requestid`)  ,
  INDEX `IDX_qu_g_passwd_requests_1` (`authid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_fieldgroups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_fieldgroups` (
  `fieldgroupid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  `rorder` TINYINT NULL DEFAULT NULL ,
  `name` VARCHAR(50) NOT NULL ,
  `data1` TEXT NULL DEFAULT NULL ,
  `data2` TEXT NULL DEFAULT NULL ,
  `data3` TEXT NULL DEFAULT NULL ,
  `data4` TEXT NULL DEFAULT NULL ,
  `data5` TEXT NULL DEFAULT NULL ,
  `data6` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`fieldgroupid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_wallpapers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_wallpapers` (
  `wallpaperid` VARCHAR(8) NOT NULL ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  `fileid` CHAR(32) NULL DEFAULT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `url` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`wallpaperid`)  ,
  INDEX `IDX_qu_g_wallpapers_1` (`accountuserid` ASC)  ,
  INDEX `IDX_qu_g_wallpapers_2` (`fileid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_importexport`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_importexport` (
  `importexportid` VARCHAR(8) NOT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `code` VARCHAR(40) NULL DEFAULT NULL COMMENT 'code must be unique',
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `classname` VARCHAR(255) NULL DEFAULT NULL ,
  `accountid` VARCHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`importexportid`)  ,
  INDEX `IDX_qu_g_importexport_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_logins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_logins` (
  `loginid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountuserid` CHAR(8) NULL DEFAULT NULL ,
  `login` DATETIME NOT NULL ,
  `lastrequest` DATETIME NOT NULL ,
  `logout` DATETIME NULL DEFAULT NULL ,
  `ip` VARCHAR(39) NOT NULL ,
  PRIMARY KEY (`loginid`)  ,
  INDEX `IDX_qu_g_logins_1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_exports`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_exports` (
  `exportid` VARCHAR(8) NOT NULL ,
  `filename` VARCHAR(255) NULL DEFAULT NULL ,
  `datetime` DATETIME NULL DEFAULT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `datatypes` VARCHAR(255) NULL DEFAULT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`exportid`)  ,
  INDEX `IDX_qu_g_exports_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_languages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_languages` (
  `languageid` CHAR(40) NOT NULL ,
  `code` VARCHAR(5) NOT NULL ,
  `name` VARCHAR(64) NOT NULL ,
  `eng_name` VARCHAR(64) NOT NULL ,
  `active` CHAR(1) NULL DEFAULT NULL ,
  `author` VARCHAR(255) NULL DEFAULT NULL ,
  `version` VARCHAR(40) NULL DEFAULT NULL ,
  `imported` DATETIME NOT NULL ,
  `datemodified` DATETIME NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `dateformat` VARCHAR(64) NULL DEFAULT NULL ,
  `timeformat` VARCHAR(64) NULL DEFAULT NULL ,
  `thousandsseparator` CHAR(1) NULL DEFAULT NULL ,
  `decimalseparator` CHAR(1) NULL DEFAULT NULL ,
  `translated` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Percentage of translated messages',
  `is_default` CHAR(1) NOT NULL DEFAULT 'N' ,
  `is_custom` CHAR(1) NOT NULL DEFAULT 'N' ,
  `text_direction` CHAR(1) NOT NULL DEFAULT 'L' ,
  PRIMARY KEY (`languageid`)  ,
  INDEX `IDX_qu_g_languages_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_versions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_versions` (
  `versionid` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(40) NOT NULL ,
  `application` VARCHAR(40) NOT NULL ,
  `done` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`versionid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_tasks` (
  `taskid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `classname` VARCHAR(120) NULL DEFAULT NULL ,
  `params` LONGTEXT NULL DEFAULT NULL ,
  `progress` TEXT NULL DEFAULT NULL ,
  `datecreated` DATETIME NULL DEFAULT NULL ,
  `datechanged` DATETIME NULL DEFAULT NULL ,
  `datefinished` DATETIME NULL DEFAULT NULL ,
  `sleepuntil` DATETIME NULL DEFAULT NULL ,
  `pid` VARCHAR(40) NULL DEFAULT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `progress_message` TEXT NULL DEFAULT NULL ,
  `is_executing` CHAR(1) NOT NULL DEFAULT 'N' ,
  `rtype` CHAR(1) NOT NULL DEFAULT 'C' COMMENT 'C - cron task, U - user task',
  `workingareafrom` INT NOT NULL DEFAULT 0 ,
  `workingareato` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`taskid`)  ,
  INDEX `IDX_qu_g_tasks_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_recurrencepresets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_recurrencepresets` (
  `recurrencepresetid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(80) NULL DEFAULT NULL ,
  `type` CHAR(1) NULL DEFAULT NULL COMMENT 'D - default (can not be deleted or modified), U - user (created by user - can be edited and deleted)',
  `startdate` DATETIME NULL DEFAULT NULL ,
  `enddate` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`recurrencepresetid`)  ,
  INDEX `IDX_qu_g_recurrencepresets_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_plannedtasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_plannedtasks` (
  `plannedtaskid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `recurrencepresetid` CHAR(8) NULL DEFAULT NULL ,
  `classname` VARCHAR(120) NULL DEFAULT NULL ,
  `params` TEXT NULL DEFAULT NULL ,
  `lastplandate` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`plannedtaskid`)  ,
  INDEX `IDX_qu_g_plannedtasks_1` (`accountid` ASC)  ,
  INDEX `IDX_qu_g_plannedtasks_2` (`recurrencepresetid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_recurrencesettings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_recurrencesettings` (
  `recurrencesettingid` CHAR(8) NOT NULL ,
  `recurrencepresetid` CHAR(8) NULL DEFAULT NULL ,
  `type` CHAR(1) NULL DEFAULT NULL COMMENT 'O - once, E - each period seconds, H - period second from start of frequency hour, D - day, W - week, M - month, Y - year',
  `period` INT NULL DEFAULT NULL ,
  `frequency` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`recurrencesettingid`)  ,
  INDEX `IDX_qu_g_recurrencesettings_1` (`recurrencepresetid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_nl_newsletters`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_nl_newsletters` (
  `newsletterid` VARCHAR(8) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `success_signup_url` TEXT NULL DEFAULT NULL ,
  `double_optin` CHAR(1) NOT NULL DEFAULT 'Y' ,
  `mailaccountid` VARCHAR(8) NULL DEFAULT NULL ,
  `optin_templateid` VARCHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`newsletterid`)  ,
  INDEX `IDX_qu_nl_newsletters_1` (`mailaccountid` ASC)  ,
  INDEX `IDX_qu_nl_newsletters_2` (`optin_templateid` ASC)  ,
  INDEX `IDX_qu_nl_newsletters_3` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_nl_broadcasts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_nl_broadcasts` (
  `broadcastid` CHAR(8) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `scheduled` DATETIME NULL DEFAULT NULL ,
  `broadcast_status` CHAR(1) NOT NULL ,
  `newsletterid` VARCHAR(8) NOT NULL ,
  `templateid` VARCHAR(8) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`broadcastid`)  ,
  INDEX `IDX_qu_nl_broadcasts_1` (`newsletterid` ASC)  ,
  INDEX `IDX_qu_nl_broadcasts_2` (`templateid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_nl_newsletter_signups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_nl_newsletter_signups` (
  `signupid` VARCHAR(32) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `subscribed` DATETIME NULL DEFAULT NULL ,
  `unsubscribed` DATETIME NULL DEFAULT NULL ,
  `signup_status` CHAR(1) NOT NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `unsubscribe_reason` TEXT NULL DEFAULT NULL ,
  `newsletterid` VARCHAR(8) NOT NULL ,
  `accountuserid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`signupid`)  ,
  INDEX `IDX_qu_nl_newsletter_signups_1` (`newsletterid` ASC)  ,
  INDEX `IDX_qu_nl_newsletter_signups_2` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_nl_user_broadcasts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_nl_user_broadcasts` (
  `broadcastid` CHAR(8) NOT NULL ,
  `signupid` VARCHAR(32) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `outboxid` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`broadcastid`, `signupid`)  ,
  INDEX `IDX_qu_nl_user_broadcasts_1` (`broadcastid` ASC)  ,
  INDEX `IDX_qu_nl_user_broadcasts_2` (`signupid` ASC)  ,
  INDEX `IDX_qu_nl_user_broadcasts_3` (`outboxid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_nl_followups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_nl_followups` (
  `followupid` CHAR(8) NOT NULL ,
  `delay_days` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `followup_status` CHAR(1) NOT NULL ,
  `delivery_hour` SMALLINT NOT NULL DEFAULT 12 ,
  `newsletterid` VARCHAR(8) NOT NULL ,
  `templateid` VARCHAR(8) NOT NULL ,
  `modified` DATETIME NOT NULL ,
  PRIMARY KEY (`followupid`)  ,
  INDEX `IDX_qu_nl_followups_1` (`newsletterid` ASC)  ,
  INDEX `IDX_qu_nl_followups_2` (`templateid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_nl_user_followups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_nl_user_followups` (
  `followupid` CHAR(40) NOT NULL ,
  `signupid` VARCHAR(32) NOT NULL ,
  `created` DATETIME NOT NULL ,
  `outboxid` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`followupid`, `signupid`)  ,
  INDEX `IDX_qu_nl_user_followups_1` (`followupid` ASC)  ,
  INDEX `IDX_qu_nl_user_followups_2` (`signupid` ASC)  ,
  INDEX `IDX_qu_nl_user_followups_3` (`outboxid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_quicktasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_quicktasks` (
  `quicktaskid` CHAR(16) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `groupid` CHAR(16) NULL DEFAULT NULL ,
  `request` LONGTEXT NULL DEFAULT NULL ,
  `validto` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`quicktaskid`)  ,
  INDEX `qu_g_accounts_qu_g_quicktasks_idx` (`accountid` ASC)  ,
  INDEX `qu_g_validto_qu_g_quicktasks_idx` (`validto` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_hierarchicaldatanodes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_hierarchicaldatanodes` (
  `nodeid` INT NOT NULL AUTO_INCREMENT COMMENT 'node id',
  `type` VARCHAR(9) NOT NULL COMMENT 'node type - used to identify nodes of one type (for example for one plugin)',
  `code` CHAR(8) NOT NULL ,
  `name` CHAR(200) NULL DEFAULT NULL COMMENT 'name od the node',
  `lft` INT NOT NULL COMMENT 'nested tree algorithm data - left',
  `rgt` INT NOT NULL COMMENT 'nested tree algorithm data - right',
  `state` CHAR(1) NOT NULL COMMENT 'state of the node - defined in child class',
  `dateinserted` DATETIME NOT NULL ,
  PRIMARY KEY (`nodeid`)  ,
  INDEX `qu_g_hierarchicaldatanodes_code` (`code` ASC)  ,
  INDEX `IDX_qu_g_hierarchicaldatanodes_type` (`type` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_jobsruns`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_jobsruns` (
  `runid` INT NOT NULL AUTO_INCREMENT ,
  `starttime` DATETIME NOT NULL COMMENT 'start time of one jobs process',
  PRIMARY KEY (`runid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_notification_registrations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_notification_registrations` (
  `notificationid` VARCHAR(255) NOT NULL ,
  `deviceid` VARCHAR(255) NOT NULL ,
  `accountuserid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `options` VARCHAR(255) NOT NULL ,
  `registration_time` DATETIME NOT NULL ,
  INDEX `fk_qu_g_notification_registrations_qu_g_users1_idx` (`accountuserid` ASC)  ,
  PRIMARY KEY (`notificationid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_currencyrates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_currencyrates` (
  `rateid` INT NOT NULL AUTO_INCREMENT ,
  `valid_from` DATETIME NOT NULL ,
  `valid_to` DATETIME NOT NULL ,
  `source_currency` VARCHAR(10) NOT NULL ,
  `target_currency` VARCHAR(10) NOT NULL ,
  `rate` DOUBLE NOT NULL ,
  `type` CHAR(5) NOT NULL ,
  PRIMARY KEY (`rateid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_sessions` (
  `sessionid` CHAR(32) NOT NULL ,
  `createddate` DATETIME NULL DEFAULT NULL ,
  `lastreaddate` DATETIME NULL DEFAULT NULL ,
  `changeddate` DATETIME NULL DEFAULT NULL ,
  `data` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`sessionid`)  ,
  UNIQUE INDEX `sessionid_UNIQUE` (`sessionid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_session_values`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_session_values` (
  `sessionid` CHAR(32) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `data` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`sessionid`, `name`)  ,
  INDEX `fk_qu_g_session_values_qu_g_sessions1` (`sessionid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_translations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_translations` (
  `translationid` INT NOT NULL AUTO_INCREMENT ,
  `languageid` CHAR(40) NOT NULL ,
  `source` TEXT BINARY NOT NULL ,
  `translation` TEXT BINARY NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `rstatus` CHAR(1) NOT NULL ,
  `customer` CHAR(1) NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`translationid`)  ,
  INDEX `fk_g_translations_languageid_idx` (`languageid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_theme_configs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_theme_configs` (
  `themeid` CHAR(8) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `module` CHAR(10) NOT NULL ,
  `author` VARCHAR(255) NULL DEFAULT NULL ,
  `url` VARCHAR(255) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `thumbnail` VARCHAR(255) NULL DEFAULT NULL ,
  `mode` CHAR(1) NOT NULL DEFAULT 'S' COMMENT 'S - single, W - window',
  `defaultwallpaper` VARCHAR(255) NULL DEFAULT NULL ,
  `enabled` CHAR(1) NOT NULL DEFAULT 'Y' ,
  `originalthemeid` CHAR(32) NOT NULL ,
  `defaultwallpaperposition` CHAR(1) NOT NULL DEFAULT 'S' ,
  `defaultbackgroundcolor` CHAR(8) NOT NULL DEFAULT '#000000' ,
  PRIMARY KEY (`themeid`)  ,
  UNIQUE INDEX `UN_qu_g_theme_configs_1` (`name` ASC, `module` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_g_theme_templates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_g_theme_templates` (
  `templateid` CHAR(8) NOT NULL ,
  `themeid` CHAR(8) NOT NULL ,
  `filename` VARCHAR(255) NOT NULL ,
  `content` LONGBLOB NULL DEFAULT NULL ,
  `datechanged` DATETIME NULL DEFAULT NULL ,
  `is_deleted` CHAR(1) NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`templateid`)  ,
  INDEX `fk_qu_g_theme_templates_qu_g_theme1` (`themeid` ASC)  ,
  UNIQUE INDEX `UN_qu_g_theme_templates_1` (`themeid` ASC, `filename` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

