-- -----------------------------------------------------
-- Table `qu_g_notification_registrations`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `qu_g_notification_registrations` (
  `notificationid` VARCHAR(255) NOT NULL ,
  `accountuserid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `registration_time` DATETIME NOT NULL ,
  INDEX `fk_qu_g_notification_registrations_qu_g_users1_idx` (`accountuserid` ASC) ,
  PRIMARY KEY (`notificationid`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;