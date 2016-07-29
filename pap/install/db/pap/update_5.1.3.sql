-- -----------------------------------------------------
-- Table `qu_pap_useragents`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_useragents` (
  `useragentid` CHAR(6) NOT NULL ,
  `useragent` TEXT NOT NULL ,
  PRIMARY KEY (`useragentid`) )
ENGINE = MyISAM;