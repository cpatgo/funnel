-- -----------------------------------------------------
-- Table `qu_pap_rawclicks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_rawclicks` (
  `clickid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL COMMENT 'R - raw (repeating clicks from same user during some period) U - unique (first click) D - declined (click declined by fraud protection)' ,
  `datetime` DATETIME NULL DEFAULT NULL ,
  `refererurl` VARCHAR(250) NULL DEFAULT NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `browser` VARCHAR(6) NULL DEFAULT NULL ,
  `cdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `cdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'processing status. null - not processed P - processed' ,
  PRIMARY KEY (`clickid`) ,
  INDEX `IDX_qu_pap_rawclicks_1` (`userid` ASC) ,
  INDEX `IDX_qu_pap_rawclicks_2` (`parentbannerid` ASC) ,
  INDEX `IDX_qu_pap_rawclicks_3` (`campaignid` ASC) ,
  INDEX `IDX_qu_pap_rawclicks_4` (`bannerid` ASC) ,
  INDEX `IDX_qu_pap_rawclicks_5` (`ip` ASC) ,
  INDEX `fk_qu_pap_rawclicks_qu_g_accounts1` (`accountid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;