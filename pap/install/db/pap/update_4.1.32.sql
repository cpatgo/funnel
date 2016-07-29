# ---------------------------------------------------------------------- #
# Add table "qu_pap_visitors"                                            #
# ---------------------------------------------------------------------- #

CREATE  TABLE IF NOT EXISTS `qu_pap_visitors` (
  `visitorid` CHAR(32) NOT NULL ,
  `name` VARCHAR(100) NULL DEFAULT NULL ,
  `email` VARCHAR(60) NULL DEFAULT NULL ,
  PRIMARY KEY (`visitorid`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- -----------------------------------------------------
-- Table `qu_pap_visitoraffiliates`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_visitoraffiliates` (
  `visitoraffiliateid` INT NOT NULL AUTO_INCREMENT ,
  `visitorid` CHAR(32) NOT NULL ,
  `userid` CHAR(8) NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`visitoraffiliateid`) ,
  INDEX `qu_pap_users_qu_pap_visitoraffiliates` (`userid` ASC) ,
  INDEX `qu_pap_banners_qu_pap_visitoraffiliates` (`bannerid` ASC) ,
  INDEX `qu_pap_campaigns_qu_pap_visitoraffiliates` (`campaignid` ASC) ,
  INDEX `IDX_qu_pap_visitoraffiliates_ip` (`ip` ASC) ,
  INDEX `qu_pap_visitors_qu_pap_visitoraffiliates` (`visitorid` ASC, `datevisit` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;