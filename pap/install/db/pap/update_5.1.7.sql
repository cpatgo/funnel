-- -----------------------------------------------------
-- Table `qu_pap_cachedpages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_pap_cachedpages` (
  `cachedpageid` INT NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(255) NOT NULL ,
  `header` TEXT NULL ,
  `content` LONGBLOB NULL ,
  `valid_until` DATETIME NULL ,
  PRIMARY KEY (`cachedpageid`) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) )
ENGINE = MyISAM;