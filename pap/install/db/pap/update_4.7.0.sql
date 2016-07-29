-- -----------------------------------------------------
-- Table `qu_pap_transactions_stats_aff`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_transactions_stats_aff` (
  `transstatid` BIGINT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL ,
  `bannerid` CHAR(8) NULL ,
  `campaignid` CHAR(8) NULL ,
  `rstatus` CHAR(1) NULL ,
  `rtype` CHAR(1) NULL ,
  `dateinserted` DATETIME NULL ,
  `payoutstatus` CHAR(1) NULL ,
  `countrycode` VARCHAR(2) NULL ,
  `tier` TINYINT NULL ,
  `commtypeid` CHAR(8) NULL ,
  `channel` VARCHAR(8) NULL ,
  `commission` DOUBLE NULL ,
  `totalcost` DOUBLE NULL ,
  `fixedcost` FLOAT NULL DEFAULT NULL ,
  `commissioncount` INT NULL ,
  `parentbannerid` CHAR(8) NULL ,
  PRIMARY KEY (`transstatid`) ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_g_accounts1` (`accountid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_users1` (`userid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_banners1` (`bannerid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_campaigns1` (`campaignid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_channels1` (`channel` ASC) ,
  INDEX `IDX_qu_pap_transactions_stats_aff_rtype` (`rtype` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- -----------------------------------------------------
-- Table `qu_pap_transactions_stats`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_transactions_stats` (
  `transstatid` BIGINT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `bannerid` CHAR(8) NULL ,
  `campaignid` CHAR(8) NULL ,
  `rstatus` CHAR(1) NULL ,
  `rtype` CHAR(1) NULL ,
  `dateinserted` DATETIME NULL ,
  `payoutstatus` CHAR(1) NULL ,
  `countrycode` VARCHAR(2) NULL ,
  `tier` TINYINT NULL ,
  `commtypeid` CHAR(8) NULL ,
  `channel` VARCHAR(8) NULL ,
  `commission` DOUBLE NULL ,
  `totalcost` DOUBLE NULL ,
  `fixedcost` FLOAT NULL DEFAULT NULL ,
  `commissioncount` INT NULL ,
  `parentbannerid` CHAR(8) NULL ,
  PRIMARY KEY (`transstatid`) ,
  INDEX `fk_qu_pap_transactions_stats_qu_g_accounts1` (`accountid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_qu_pap_banners1` (`bannerid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_qu_pap_campaigns1` (`campaignid` ASC) ,
  INDEX `fk_qu_pap_transactions_stats_qu_pap_channels1` (`channel` ASC) ,
  INDEX `IDX_qu_pap_transactions_stats_rtype` (`rtype` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- -----------------------------------------------------
-- Table `qu_pap_transstatsdays`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_transstatsdays` (
  `dateinserted` DATE NOT NULL ,
  PRIMARY KEY (`dateinserted`) )
ENGINE = MyISAM;

TRUNCATE qu_pap_transstatsdays;