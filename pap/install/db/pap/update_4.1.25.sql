CREATE  TABLE IF NOT EXISTS `qu_pap_affiliatetrackingcodes` (
  `affiliatetrackingcodeid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `code` LONGTEXT NULL DEFAULT NULL ,
  `note` LONGTEXT NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`affiliatetrackingcodeid`) ,
  INDEX `qu_pap_commissiontypes_qu_pap_affiliatetrackingcodes` (`commtypeid` ASC) ,
  INDEX `qu_pap_users_qu_pap_affiliatetrackingcodes` (`userid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;