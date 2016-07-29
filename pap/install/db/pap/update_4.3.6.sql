-- -----------------------------------------------------
-- Table `pap4`.`qu_pap_clicks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_clicks` (
  `clickid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `cdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `cdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `raw` INT UNSIGNED NULL DEFAULT 0 ,
  `uniq` INT UNSIGNED NULL DEFAULT 0 ,
  `declined` INT UNSIGNED NULL DEFAULT 0 ,
  PRIMARY KEY (`clickid`) ,
  INDEX `IDX_qu_pap_dailyclicks_1` (`userid` ASC, `campaignid` ASC, `bannerid` ASC, `dateinserted` ASC) ,
  INDEX `IDX_qu_pap_dailyclicks_2` (`campaignid` ASC) ,
  INDEX `IDX_qu_pap_dailyclicks_3` (`bannerid` ASC) ,
  INDEX `IDX_qu_pap_dailyclicks_4` (`parentbannerid` ASC) ,
  INDEX `qu_pap_users_qu_pap_dailyclicks` (`userid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



-- -----------------------------------------------------
-- Table `pap4`.`qu_pap_impressions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_impressions` (
  `impressionid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `cdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `cdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `raw` INT UNSIGNED NULL DEFAULT 0 COMMENT 'raw clicks from 0:00 to 1:00' ,
  `uniq` INT UNSIGNED NULL DEFAULT 0 ,
  PRIMARY KEY (`impressionid`) ,
  INDEX `IDX_qu_pap_dailyimpressions_1` (`userid` ASC, `campaignid` ASC, `bannerid` ASC, `dateinserted` ASC) ,
  INDEX `IDX_qu_pap_dailyimpressions_2` (`campaignid` ASC) ,
  INDEX `IDX_qu_pap_dailyimpressions_3` (`bannerid` ASC) ,
  INDEX `IDX_qu_pap_dailyimpressions_4` (`parentbannerid` ASC) ,
  INDEX `qu_pap_users_qu_pap_dailyimpressions` (`userid` ASC) ,
  INDEX `IDX_qu_pap_dailyimpressions_5` (`dateinserted` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
