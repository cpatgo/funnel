CREATE  TABLE IF NOT EXISTS `qu_pap_keywords` (
  `keywordid` CHAR(8) NOT NULL ,
  `keyword_text` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`keywordid`) ,
  UNIQUE INDEX `keyword_text_UNIQUE` (`keyword_text` ASC) )
ENGINE = MyISAM;


CREATE  TABLE IF NOT EXISTS `qu_pap_keywordstats` (
  `keywordstatid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `keywordid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `dateinserted` DATETIME NOT NULL ,
  `commtypeid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `sales` INT UNSIGNED NULL DEFAULT 0 ,
  `totalcost` FLOAT NULL DEFAULT 0 ,
  `commissions` FLOAT NULL ,
  PRIMARY KEY (`keywordstatid`) ,
  INDEX `fk_qu_pap_keywordstats_qu_pap_keywords1` (`keywordid` ASC) ,
  INDEX `fk_qu_pap_keywordstats_qu_pap_users1` (`userid` ASC) ,
  INDEX `fk_qu_pap_keywordstats_qu_g_accounts1` (`accountid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



CREATE  TABLE IF NOT EXISTS `qu_pap_keywordclicks` (
  `keywordclickid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NOT NULL ,
  `keywordid` CHAR(8) NOT NULL ,
  `clicks` INT UNSIGNED NULL DEFAULT 0 ,
  `dateinserted` DATETIME NOT NULL ,
  PRIMARY KEY (`keywordclickid`) ,
  INDEX `fk_qu_pap_keywordclicks_qu_g_accounts1` (`accountid` ASC) ,
  INDEX `fk_qu_pap_keywordclicks_qu_pap_users1` (`userid` ASC) ,
  INDEX `fk_qu_pap_keywordclicks_qu_pap_keywords1` (`keywordid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
