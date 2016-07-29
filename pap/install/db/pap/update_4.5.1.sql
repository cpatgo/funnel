CREATE  TABLE IF NOT EXISTS `qu_pap_invoices` (
  `invoiceid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `datecreated` DATETIME NULL ,
  `duedate` DATETIME NULL ,
  `datefrom` DATETIME NULL ,
  `dateto` DATETIME NULL ,
  `rstatus` CHAR(1) NULL COMMENT 'U - unpaid\\nP - paid' ,
  `number` VARCHAR(40) NULL ,
  `amount` FLOAT NULL ,
  `merchantnote` LONGTEXT NULL ,
  `systemnote` LONGTEXT NULL ,
  `proformatext` LONGTEXT NULL ,
  `invoicetext` LONGTEXT NULL ,
  PRIMARY KEY (`invoiceid`) ,
  INDEX `fk_qu_pap_invoices_qu_g_accounts1` (`accountid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE qu_pap_invoices ADD datepaid DATETIME NULL;