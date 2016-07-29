-- -----------------------------------------------------
-- Table `qu_pap_visitornonrefclicks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visitornonrefclicks` ( 
    `visitornonrefclickid` INT NOT NULL AUTO_INCREMENT , 
    `visitorid` CHAR(32) NOT NULL , 
    `ip` VARCHAR(39) NULL DEFAULT NULL , 
    `datevisit` DATETIME NULL DEFAULT NULL , 
    `referrerurl` TEXT NULL DEFAULT NULL , 
    `accountid` CHAR(8) NOT NULL , 
    PRIMARY KEY (`visitornonrefclickid`) , 
    INDEX `fk_qu_pap_visitornonrefclicks_qu_g_accounts1` (`accountid` ASC) , 
    INDEX `IDX_qu_pap_visitornonrefclicks_ip` (`ip` ASC) , 
    INDEX `IDX_qu_pap_visitornonrefclicks_visitorid` (`visitorid` ASC, `datevisit` ASC) )
ENGINE = InnoDB 
DEFAULT CHARACTER SET = utf8 
COLLATE = utf8_general_ci;