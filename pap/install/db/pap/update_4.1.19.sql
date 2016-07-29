ALTER TABLE qu_pap_campaigns CHANGE productid productid LONGTEXT ASCII;

ALTER TABLE qu_pap_transactions ADD couponid CHAR(8) ASCII AFTER channel;

CREATE  TABLE IF NOT EXISTS `qu_pap_coupons` (
  `couponid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `couponcode` VARCHAR(100) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  `validfrom` DATETIME NULL DEFAULT NULL ,
  `validto` DATETIME NULL DEFAULT NULL ,
  `maxusecount` INT NULL DEFAULT NULL ,
  `usecount` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`couponid`) ,
  INDEX `qu_pap_users_qu_pap_coupons` (`userid` ASC) ,
  INDEX `qu_pap_banners_qu_pap_coupons` (`bannerid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


ALTER TABLE qu_pap_transactions ADD CONSTRAINT qu_pap_coupons_qu_pap_transactions
    FOREIGN KEY (couponid) REFERENCES qu_pap_coupons (couponid);
