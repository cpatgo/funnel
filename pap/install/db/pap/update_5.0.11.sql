-- -----------------------------------------------------
-- Table `qu_pap_userrecipients`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_userrecipients` (
  `userrecipientid` INT NOT NULL AUTO_INCREMENT ,
  `userid` CHAR(8) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`userrecipientid`) ,
  INDEX `fk_qu_pap_userrecipients_qu_pap_users1` (`userid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;