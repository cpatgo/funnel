-- -----------------------------------------------------
-- Table `qu_pap_news`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_news` (
  `newsid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `dateinserted` DATETIME NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`newsid`) )
ENGINE = MyISAM;