CREATE  TABLE IF NOT EXISTS `qu_g_quicktasks` (
  `quicktaskid` CHAR(16) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `groupid` CHAR(16) NULL DEFAULT NULL ,
  `request` LONGTEXT NULL DEFAULT NULL ,
  `validto` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`quicktaskid`) ,
  INDEX `qu_g_accounts_qu_g_quicktasks_idx` (`accountid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;