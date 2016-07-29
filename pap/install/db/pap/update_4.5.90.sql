CREATE  TABLE IF NOT EXISTS `qu_pap_campaignrules` (
  `ruleid` CHAR(8) NOT NULL ,
  `campaignid` CHAR(8) NOT NULL ,
  `stats_what` VARCHAR(1) NULL DEFAULT NULL COMMENT 'S - number of sales, C - value of commissions, T- value of total cost, D - datetime condition' ,
  `status` VARCHAR(1) NULL DEFAULT NULL COMMENT 'A - Approved, P - Pending, D - Declined, O - Approved or Pending' ,
  `stats_date` VARCHAR(3) NULL DEFAULT NULL COMMENT 'AM - Actual month, AY - Actual year, AUC - All unpaid commissions, LW - Last week, LTW - Last two weeks, LM - Last month, AT - All time, SD - Since day of last month' ,
  `stats_since` INT NULL DEFAULT NULL ,
  `equation` VARCHAR(1) NULL DEFAULT NULL COMMENT 'L - lower than, H - higher than, B - between, E - equal to' ,
  `equationvalue1` FLOAT NULL DEFAULT NULL ,
  `equationvalue2` FLOAT NULL DEFAULT NULL ,
  `time_type` VARCHAR(1) NULL DEFAULT NULL COMMENT 'D - daily, M - monthly, W - weekly, T - specific time' ,
  `time_day` VARCHAR(2) NULL DEFAULT NULL ,
  `time_date` DATE NULL DEFAULT NULL ,
  `time_hour` VARCHAR(2) NULL DEFAULT NULL ,
  `status_to` VARCHAR(1) NOT NULL COMMENT 'change campaign status to: A - active, S - stopped/disabled, invisible to affiliates, W - stopped' ,
  PRIMARY KEY (`ruleid`) ,
  INDEX `IDX_qu_pap_rules_1` (`campaignid` ASC))
ENGINE = InnoDb;