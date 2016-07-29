-- -----------------------------------------------------
-- Table `qu_pap_campaignattributes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_campaignattributes` (
  `attributeid` CHAR(8) NOT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`attributeid`) ,
  INDEX `qu_pap_campaigns_qu_pap_campaignattributes` (`campaignid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;