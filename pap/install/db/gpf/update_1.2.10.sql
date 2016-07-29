-- -----------------------------------------------------
-- Table `qu_g_sessions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_g_sessions` (
  `sessionid` CHAR(32) NOT NULL ,
  `createddate` DATETIME NULL DEFAULT NULL ,
  `lastreaddate` DATETIME NULL DEFAULT NULL ,
  `changeddate` DATETIME NULL DEFAULT NULL ,
  `data` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`sessionid`) ,
  UNIQUE INDEX `sessionid_UNIQUE` (`sessionid` ASC) )
ENGINE = InnoDb;


-- -----------------------------------------------------
-- Table `qu_g_session_values`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_g_session_values` (
  `sessionid` CHAR(32) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `data` LONGTEXT NULL ,
  PRIMARY KEY (`sessionid`, `name`) ,
  INDEX `fk_qu_g_session_values_qu_g_sessions1` (`sessionid` ASC))
ENGINE = InnoDb;
