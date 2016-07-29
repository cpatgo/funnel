CREATE  TABLE IF NOT EXISTS `qu_pap_commissiontypeattributes` (
  `attributeid` CHAR(8) NOT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`attributeid`) ,
  INDEX `qu_pap_commissiontypes_qu_pap_commissiontypeattributes` (`commtypeid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;