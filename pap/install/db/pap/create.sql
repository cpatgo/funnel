-- -----------------------------------------------------
-- Table `qu_pap_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_users` (
  `userid` CHAR(8) NOT NULL ,
  `accountuserid` CHAR(8) NOT NULL ,
  `parentuserid` CHAR(8) NULL DEFAULT NULL ,
  `refid` VARCHAR(128) NOT NULL ,
  `numberuserid` INT NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL COMMENT 'A-affiliate M-merchant',
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `dateapproved` DATETIME NULL DEFAULT NULL ,
  `deleted` CHAR(1) NULL DEFAULT NULL ,
  `minimumpayout` VARCHAR(20) NOT NULL DEFAULT '300' ,
  `note` TEXT NULL DEFAULT NULL ,
  `photo` VARCHAR(255) NULL DEFAULT NULL ,
  `payoutoptionid` VARCHAR(8) NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `data3` VARCHAR(255) NULL DEFAULT NULL ,
  `data4` VARCHAR(255) NULL DEFAULT NULL ,
  `data5` VARCHAR(255) NULL DEFAULT NULL ,
  `data6` VARCHAR(255) NULL DEFAULT NULL ,
  `data7` VARCHAR(255) NULL DEFAULT NULL ,
  `data8` VARCHAR(255) NULL DEFAULT NULL ,
  `data9` VARCHAR(255) NULL DEFAULT NULL ,
  `data10` VARCHAR(255) NULL DEFAULT NULL ,
  `data11` VARCHAR(255) NULL DEFAULT NULL ,
  `data12` VARCHAR(255) NULL DEFAULT NULL ,
  `data13` VARCHAR(255) NULL DEFAULT NULL ,
  `data14` VARCHAR(255) NULL DEFAULT NULL ,
  `data15` VARCHAR(255) NULL DEFAULT NULL ,
  `data16` VARCHAR(255) NULL DEFAULT NULL ,
  `data17` VARCHAR(255) NULL DEFAULT NULL ,
  `data18` VARCHAR(255) NULL DEFAULT NULL ,
  `data19` VARCHAR(255) NULL DEFAULT NULL ,
  `data20` VARCHAR(255) NULL DEFAULT NULL ,
  `data21` VARCHAR(255) NULL DEFAULT NULL ,
  `data22` VARCHAR(255) NULL DEFAULT NULL ,
  `data23` VARCHAR(255) NULL DEFAULT NULL ,
  `data24` VARCHAR(255) NULL DEFAULT NULL ,
  `data25` VARCHAR(255) NULL DEFAULT NULL ,
  `originalparentuserid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`userid`)  ,
  UNIQUE INDEX `UC_qu_pap_users_refid` (`refid` ASC)  ,
  INDEX `IDX_qu_pap_users_2` (`parentuserid` ASC)  ,
  INDEX `IDX_qu_pap_users_3` (`payoutoptionid` ASC)  ,
  INDEX `fk_qu_pap_users_qu_g_users1` (`accountuserid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_campaigns`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_campaigns` (
  `campaignid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NOT NULL COMMENT 'campaign type:  P - public - visible to all, M - public with manual approval, I - visible only for invioted affiliates',
  `rstatus` CHAR(1) NOT NULL COMMENT 'campaign status: A - active, S - stopped/disabled, invisible to affiliates, W - stopped, visible to affiliates as stoped',
  `name` VARCHAR(100) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `dateinserted` DATETIME NOT NULL ,
  `rorder` INT NOT NULL DEFAULT 0 COMMENT 'order of displaying campaigns in affiliate panel',
  `networkstatus` CHAR(1) NOT NULL DEFAULT 'I' ,
  `isdefault` CHAR(1) NOT NULL DEFAULT 'N' ,
  `logourl` VARCHAR(255) NULL DEFAULT NULL ,
  `productid` LONGTEXT NULL DEFAULT NULL ,
  `discontinueurl` VARCHAR(255) NULL DEFAULT NULL ,
  `validfrom` DATETIME NULL DEFAULT NULL COMMENT 'only for rstatus = T',
  `validto` DATETIME NULL DEFAULT NULL COMMENT 'only for rstatus = T',
  `validnumber` INT UNSIGNED NULL DEFAULT NULL COMMENT 'only for rstatus = L',
  `validtype` CHAR(1) NULL DEFAULT NULL COMMENT 'type of transactions for validity: C - clicks, S - sales, L - leads',
  `cookielifetime` SMALLINT UNSIGNED NOT NULL DEFAULT 0 ,
  `overwritecookie` CHAR(1) NOT NULL DEFAULT 'N' COMMENT 'Y - overwrite N - not overwrite',
  `linkingmethod` CHAR(1) NOT NULL DEFAULT 0 ,
  `countries` TEXT NULL DEFAULT NULL COMMENT 'allowed countries for Geo IP',
  `geocampaigndisplay` CHAR(1) NULL DEFAULT 'N' COMMENT 'if Y, then it will not display campaign to affiliates from country other than allowed',
  `geobannersshow` CHAR(1) NULL DEFAULT 'N' COMMENT 'if Y, then it will not display banners on sites from country other than allowed',
  `geotransregister` CHAR(1) NULL DEFAULT 'N' COMMENT 'if Y, then it will not register imp/click/sale/lead transactions from country other than allowed',
  `longdescription` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`campaignid`)  ,
  INDEX `fk_qu_pap_campaigns_qu_g_accounts1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_bannerwrappers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_bannerwrappers` (
  `wrapperid` CHAR(8) NOT NULL ,
  `name` VARCHAR(80) NULL DEFAULT NULL ,
  `code` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`wrapperid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_banners`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_banners` (
  `bannerid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `wrapperid` CHAR(8) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NOT NULL COMMENT 'Banner type: F - flash, H - html, I - image, L - landingpage, O - offline, V - pdf, P - popup, E - promoemail, R - rotator, T - textlink, A - link, C - coupon, U - lightbox, Y - pagepeel, X - rebrandpdf, S - replicated, Z - zip, N - pagepeelhtml',
  `rstatus` CHAR(1) NOT NULL COMMENT 'Banner status: A - active H - inactive & hidden',
  `isconfirmed` CHAR(1) NOT NULL DEFAULT 'Y' ,
  `name` VARCHAR(150) NULL DEFAULT NULL ,
  `destinationurl` TEXT NOT NULL ,
  `target` VARCHAR(10) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NOT NULL ,
  `size` VARCHAR(50) NULL DEFAULT NULL COMMENT 'size of banner: U - undefined P - predefined, format: P:120x40 O - own, format: 25x25',
  `data1` TEXT NULL DEFAULT NULL ,
  `data2` TEXT NULL DEFAULT NULL ,
  `data3` TEXT NULL DEFAULT NULL ,
  `data4` TEXT NULL DEFAULT NULL ,
  `data5` TEXT NULL DEFAULT NULL ,
  `data6` TEXT NULL DEFAULT NULL ,
  `data7` TEXT NULL DEFAULT NULL ,
  `data8` TEXT NULL DEFAULT NULL ,
  `data9` TEXT NULL DEFAULT NULL ,
  `rorder` INT NOT NULL DEFAULT 0 COMMENT 'determines banners order',
  `description` TEXT NULL DEFAULT NULL ,
  `seostring` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`bannerid`)  ,
  INDEX `IDX_qu_pap_banners_2` (`campaignid` ASC)  ,
  INDEX `qu_pap_bannerwrappers_qu_pap_banners` (`wrapperid` ASC)  ,
  INDEX `fk_qu_pap_banners_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_banners_3` (`rorder` ASC, `dateinserted` DESC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_commissiontypes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_commissiontypes` (
  `commtypeid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL COMMENT 'I - CPM C - per click S - per sale A - per action',
  `rstatus` CHAR(1) NOT NULL COMMENT 'status: E - enabled D - disabled',
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `approval` CHAR(1) NULL DEFAULT NULL COMMENT 'transactions approval: M - manual A - automatic',
  `code` VARCHAR(20) NULL DEFAULT NULL COMMENT 'tracking code for sub tracking this campaign (Product ID)',
  `zeroorderscommission` CHAR(1) NULL DEFAULT NULL COMMENT 'commissions on zero orders Y or N',
  `fixedcosttype` CHAR(1) NULL DEFAULT NULL ,
  `fixedcostvalue` FLOAT NULL DEFAULT NULL ,
  `recurrencepresetid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `countrycodes` TEXT NULL DEFAULT NULL ,
  `parentcommtypeid` VARCHAR(8) NULL DEFAULT NULL ,
  `savezerocommission` CHAR(1) NULL DEFAULT NULL ,
  `cities` TEXT NULL DEFAULT NULL ,
  `locationtype` CHAR(1) NULL DEFAULT 'C' ,
  PRIMARY KEY (`commtypeid`)  ,
  INDEX `IDX_qu_pap_commissiontypes_1` (`campaignid` ASC)  ,
  INDEX `IDX_qu_pap_commissiontypes_2` (`recurrencepresetid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_payouthistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_payouthistory` (
  `payouthistoryid` CHAR(8) NOT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `amount` FLOAT NULL DEFAULT NULL ,
  `userscount` INT NULL DEFAULT NULL ,
  `merchantnote` TEXT NULL DEFAULT NULL ,
  `affiliatenote` TEXT NULL DEFAULT NULL ,
  `datefrom` DATETIME NULL DEFAULT NULL COMMENT 'transactions from',
  `dateto` DATETIME NULL DEFAULT NULL COMMENT 'transactions to',
  `exportfile` VARCHAR(200) NULL DEFAULT NULL COMMENT 'file with export data',
  `accountid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`payouthistoryid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_coupons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_coupons` (
  `couponid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `couponcode` VARCHAR(100) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  `validfrom` DATETIME NULL DEFAULT NULL ,
  `validto` DATETIME NULL DEFAULT NULL ,
  `maxusecount` INT NULL DEFAULT NULL ,
  `usecount` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`couponid`)  ,
  INDEX `qu_pap_users_qu_pap_coupons` (`userid` ASC)  ,
  INDEX `qu_pap_banners_qu_pap_coupons` (`bannerid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_commissiongroups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_commissiongroups` (
  `commissiongroupid` CHAR(8) NOT NULL ,
  `isdefault` CHAR(40) NOT NULL DEFAULT 'N' COMMENT 'Y - Yes N - No',
  `name` VARCHAR(60) NOT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `cookielifetime` SMALLINT NOT NULL DEFAULT -1 COMMENT '-1 - same as campaign',
  `priority` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`commissiongroupid`)  ,
  INDEX `IDX_qu_pap_commissiongroups_1` (`campaignid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_commissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_commissions` (
  `commissionid` CHAR(8) NOT NULL ,
  `tier` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `subtype` CHAR(1) NOT NULL COMMENT 'sub type of commission: N - normal R - recurring S - sub sales D - sub sales recurring',
  `commissiontype` CHAR(1) NOT NULL COMMENT '$ or %',
  `commissionvalue` FLOAT NOT NULL ,
  `commissiongroupid` CHAR(8) NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`commissionid`)  ,
  INDEX `IDX_qu_pap_commissions_1` (`commissiongroupid` ASC)  ,
  INDEX `IDX_qu_pap_commissions_2` (`commtypeid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_transactions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_transactions` (
  `transid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `parenttransid` CHAR(8) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'A - approved P - pending D - declined',
  `rtype` CHAR(1) NULL DEFAULT NULL COMMENT 'S - sale B - signup bonus R - refund C - click I - CPM E - Extra bonus',
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `dateapproved` DATETIME NULL DEFAULT NULL ,
  `payoutstatus` CHAR(1) NOT NULL DEFAULT 'U' COMMENT 'U - unpaid P - paid',
  `refererurl` TEXT NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `browser` VARCHAR(6) NULL DEFAULT NULL ,
  `commission` DOUBLE NULL DEFAULT NULL ,
  `split` FLOAT NOT NULL DEFAULT 1 ,
  `recurringcommid` CHAR(8) NULL DEFAULT NULL ,
  `clickcount` INT UNSIGNED NULL DEFAULT NULL ,
  `firstclicktime` DATETIME NULL DEFAULT NULL ,
  `firstclickreferer` TEXT NULL DEFAULT NULL ,
  `firstclickip` VARCHAR(39) NULL DEFAULT NULL ,
  `firstclickdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `firstclickdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `lastclicktime` DATETIME NULL DEFAULT NULL ,
  `lastclickreferer` TEXT NULL DEFAULT NULL ,
  `lastclickip` VARCHAR(39) NULL DEFAULT NULL ,
  `lastclickdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `lastclickdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `trackmethod` CHAR(1) NULL DEFAULT 'U' ,
  `orderid` VARCHAR(255) NULL DEFAULT NULL ,
  `productid` VARCHAR(255) NULL DEFAULT NULL ,
  `totalcost` DOUBLE NULL DEFAULT NULL ,
  `fixedcost` FLOAT NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `data3` VARCHAR(255) NULL DEFAULT NULL ,
  `data4` VARCHAR(255) NULL DEFAULT NULL ,
  `data5` VARCHAR(255) NULL DEFAULT NULL ,
  `systemnote` VARCHAR(250) NULL DEFAULT NULL COMMENT 'note from system to merchant',
  `merchantnote` VARCHAR(250) NULL DEFAULT NULL COMMENT 'note from merchant to affiliate',
  `originalcurrencyid` CHAR(8) NULL DEFAULT NULL ,
  `originalcurrencyvalue` FLOAT NULL DEFAULT NULL ,
  `originalcurrencyrate` FLOAT NULL DEFAULT NULL ,
  `tier` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'transaction tier',
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `commissiongroupid` CHAR(8) NULL DEFAULT NULL ,
  `payouthistoryid` CHAR(8) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NOT NULL DEFAULT '' ,
  `couponid` CHAR(8) NULL DEFAULT NULL ,
  `visitorid` CHAR(32) NULL DEFAULT NULL ,
  `saleid` CHAR(8) NULL DEFAULT NULL ,
  `loggroupid` VARCHAR(16) NULL DEFAULT NULL ,
  `allowfirstclickdata` VARCHAR(1) NULL DEFAULT NULL ,
  `allowlastclickdata` VARCHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`transid`)  ,
  INDEX `IDX_qu_pap_transactions_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_2` (`bannerid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_3` (`campaignid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_4` (`parentbannerid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_5` (`parenttransid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_6` (`commtypeid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_7` (`payouthistoryid` ASC, `payoutstatus` ASC)  ,
  INDEX `qu_pap_coupons_qu_pap_transactions` (`couponid` ASC)  ,
  INDEX `fk_qu_pap_transactions_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_dateinserted` (`dateinserted` ASC)  ,
  INDEX `qu_pap_commissiongroups_qu_pap_transactions` (`commissiongroupid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_orderid` (`orderid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_data1` (`data1` ASC)  ,
  INDEX `IDX_qu_pap_transactions_data2` (`data2` ASC)  ,
  INDEX `IDX_qu_pap_transactions_rtype` (`rtype` ASC)  ,
  INDEX `IDX_qu_pap_transactions_data5` (`data5` ASC)  ,
  INDEX `IDX_qu_pap_transactions_data3` (`data3` ASC)  ,
  INDEX `IDX_qu_pap_transactions_data4` (`data4` ASC)  ,
  INDEX `IDX_qu_pap_transactions_productid` (`productid` ASC)  ,
  INDEX `IDX_qu_pap_transactions_ip` (`ip` ASC)  ,
  INDEX `IDX_qu_pap_transactions_trackmethod` (`trackmethod` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_userincommissiongroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_userincommissiongroup` (
  `usercommgroupid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `commissiongroupid` CHAR(8) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NOT NULL ,
  `note` TEXT NULL DEFAULT NULL ,
  `dateadded` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`usercommgroupid`)  ,
  INDEX `IDX_qu_pap_userincommissiongroup_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_userincommissiongroup_2` (`commissiongroupid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_rawclicks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_rawclicks` (
  `clickid` CHAR(32) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL COMMENT 'R - raw (repeating clicks from same user during some period) U - unique (first click) D - declined (click declined by fraud protection)',
  `datetime` DATETIME NULL DEFAULT NULL ,
  `refererurl` TEXT NULL DEFAULT NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `browser` VARCHAR(6) NULL DEFAULT NULL ,
  `cdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `cdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`clickid`)  ,
  INDEX `IDX_qu_pap_rawclicks_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_2` (`parentbannerid` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_3` (`campaignid` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_4` (`bannerid` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_5` (`ip` ASC)  ,
  INDEX `fk_qu_pap_rawclicks_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_6` (`datetime` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_7` (`channel` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_data1` (`cdata1` ASC)  ,
  INDEX `IDX_qu_pap_rawclicks_data2` (`cdata2` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_clicks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_clicks` (
  `clickid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `cdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `cdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `raw` INT UNSIGNED NULL DEFAULT 0 ,
  `uniq` INT UNSIGNED NULL DEFAULT 0 ,
  `declined` INT UNSIGNED NULL DEFAULT 0 ,
  PRIMARY KEY (`clickid`)  ,
  INDEX `IDX_qu_pap_dailyclicks_1` (`userid` ASC, `campaignid` ASC, `bannerid` ASC, `dateinserted` ASC)  ,
  INDEX `IDX_qu_pap_dailyclicks_2` (`campaignid` ASC)  ,
  INDEX `IDX_qu_pap_dailyclicks_3` (`bannerid` ASC)  ,
  INDEX `IDX_qu_pap_dailyclicks_4` (`parentbannerid` ASC)  ,
  INDEX `fk_qu_pap_clicks_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_clicks_dateinserted` (`dateinserted` ASC)  ,
  INDEX `qu_pap_users_qu_pap_dailyclicks` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_impressions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_impressions` (
  `impressionid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` VARCHAR(8) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `cdata1` VARCHAR(255) NULL DEFAULT NULL ,
  `cdata2` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `raw` INT UNSIGNED NULL DEFAULT 0 ,
  `uniq` INT UNSIGNED NULL DEFAULT 0 ,
  PRIMARY KEY (`impressionid`)  ,
  INDEX `IDX_qu_pap_dailyimpressions_1` (`userid` ASC, `campaignid` ASC, `bannerid` ASC, `dateinserted` ASC)  ,
  INDEX `IDX_qu_pap_dailyimpressions_2` (`campaignid` ASC)  ,
  INDEX `IDX_qu_pap_dailyimpressions_3` (`bannerid` ASC)  ,
  INDEX `IDX_qu_pap_dailyimpressions_4` (`parentbannerid` ASC)  ,
  INDEX `fk_qu_pap_impressions_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `qu_pap_users_qu_pap_dailyimpressions` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_dailyimpressions_5` (`dateinserted` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_channels`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_channels` (
  `channelid` VARCHAR(8) NOT NULL ,
  `userid` VARCHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `channel` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`channelid`)  ,
  INDEX `IDX_qu_pap_channels_1` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_directlinkurls`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_directlinkurls` (
  `directlinkurlid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `url` VARCHAR(255) NULL DEFAULT NULL COMMENT 'must be unique',
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'A - approved D - declined P - pending',
  `note` TEXT NULL DEFAULT NULL ,
  `channelid` VARCHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `matches` INT UNSIGNED ZEROFILL NULL DEFAULT 0 ,
  PRIMARY KEY (`directlinkurlid`)  ,
  INDEX `IDX_qu_pap_directlinkurls_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_directlinkurls_2` (`channelid` ASC)  ,
  INDEX `IDX_qu_pap_directlinkurls_3` (`campaignid` ASC)  ,
  INDEX `IDX_qu_pap_directlinkurls_4` (`bannerid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_payout`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_payout` (
  `payoutid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `payouthistoryid` CHAR(8) NULL DEFAULT NULL ,
  `affiliatenote` TEXT NULL DEFAULT NULL ,
  `amount` FLOAT NULL DEFAULT NULL ,
  `currencyid` CHAR(8) NULL DEFAULT NULL ,
  `invoice` MEDIUMTEXT NULL DEFAULT NULL COMMENT 'generated invoice for payment',
  `invoicenumber` INT NULL DEFAULT 0 ,
  PRIMARY KEY (`payoutid`)  ,
  INDEX `IDX_qu_pap_payout_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_payout_2` (`payouthistoryid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_affiliatescreens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_affiliatescreens` (
  `affiliatescreenid` VARCHAR(8) NOT NULL ,
  `accountid` CHAR(8) NULL DEFAULT NULL ,
  `code` VARCHAR(255) NOT NULL ,
  `params` VARCHAR(255) NULL DEFAULT NULL ,
  `title` VARCHAR(255) NULL DEFAULT NULL ,
  `icon` VARCHAR(255) NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `showheader` CHAR(1) NULL DEFAULT NULL COMMENT 'Y - show N - do not show',
  PRIMARY KEY (`affiliatescreenid`)  ,
  INDEX `IDX_qu_pap_affiliatescreens_1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_userpayoutoptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_userpayoutoptions` (
  `userid` CHAR(8) NOT NULL ,
  `formfieldid` INT UNSIGNED NOT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`userid`, `formfieldid`)  ,
  INDEX `IDX_qu_pap_userpayoutoptions_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_userpayoutoptions_2` (`formfieldid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_lifetime_referrals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_lifetime_referrals` (
  `liferefid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NOT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `channelid` CHAR(8) NULL DEFAULT NULL ,
  `identifier` VARCHAR(60) NOT NULL ,
  PRIMARY KEY (`liferefid`)  ,
  INDEX `IDX_qu_pap_lifetime_referrals_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_lifetime_referrals_2` (`identifier` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_cpmcommissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_cpmcommissions` (
  `userid` CHAR(8) NOT NULL ,
  `bannerid` CHAR(8) NOT NULL ,
  `count` INT NULL DEFAULT NULL COMMENT 'Number of impressions per user and banner. When count = 1000 transaction with CPM commission is created and count is set back to 0.',
  PRIMARY KEY (`userid`, `bannerid`)  ,
  INDEX `IDX_qu_pap_cpmcommissions_1` (`userid` ASC)  ,
  INDEX `IDX_qu_pap_cpmcommissions_2` (`bannerid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_rules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_rules` (
  `ruleid` INT NOT NULL AUTO_INCREMENT ,
  `campaignid` CHAR(8) NOT NULL ,
  `what` VARCHAR(1) NOT NULL COMMENT 'S - number of sales C - value of commissions T- value of total cost',
  `tier` INT NULL DEFAULT NULL ,
  `status` VARCHAR(1) NOT NULL COMMENT 'A - Approved P - Pending D - Declined O - Approved or Pending',
  `date` VARCHAR(4) NOT NULL COMMENT 'AM - Actual month, AY - Actual year, AUC - All unpaid commissions, LW - Last week, LTW - Last two weeks, LM - Last month, AT - All time, SD - Since day of last month, L7D - Last 7 days, L30D - Last 30 days',
  `since` INT NULL DEFAULT NULL ,
  `equation` VARCHAR(1) NOT NULL COMMENT 'L - lower than H - higher than B - between E - equal to',
  `equationvalue1` FLOAT NOT NULL ,
  `equationvalue2` FLOAT NULL DEFAULT NULL ,
  `action` VARCHAR(3) NOT NULL COMMENT 'CG - put affiliate into commission group CGR - put affiliate into commission group (retroactively) BC - add bonus commission',
  `commissiongroupid` VARCHAR(8) NULL DEFAULT NULL ,
  `bonustype` VARCHAR(1) NULL DEFAULT NULL COMMENT '$ %',
  `bonusvalue` FLOAT NULL DEFAULT NULL ,
  `computeallcampaigns` CHAR(1) NOT NULL DEFAULT 'N' ,
  `commissiongroupstatus` VARCHAR(1) NOT NULL DEFAULT '' COMMENT 'A - approved, P - pending, D - declined, X - fixed, R - ascending, F - descending',
  PRIMARY KEY (`ruleid`)  ,
  INDEX `IDX_qu_pap_rules_1` (`commissiongroupid` ASC)  ,
  INDEX `IDX_qu_pap_rules_2` (`campaignid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_bannersinrotators`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_bannersinrotators` (
  `bannerinrotatorid` CHAR(8) NOT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  `rotatedbannerid` CHAR(8) NULL DEFAULT NULL ,
  `all_imps` INT NULL DEFAULT 0 ,
  `uniq_imps` INT NULL DEFAULT 0 ,
  `clicks` INT NULL DEFAULT 0 ,
  `rank` FLOAT NULL DEFAULT NULL ,
  `valid_from` DATETIME NULL DEFAULT NULL ,
  `valid_until` DATETIME NULL DEFAULT NULL ,
  `archive` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`bannerinrotatorid`)  ,
  INDEX `IDX_qu_pap_bannersinrotators_1` (`parentbannerid` ASC)  ,
  INDEX `IDX_qu_pap_bannersinrotators_2` (`rotatedbannerid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_recurringcommissions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_recurringcommissions` (
  `recurringcommissionid` CHAR(8) NOT NULL ,
  `transid` CHAR(8) NULL DEFAULT NULL ,
  `orderid` VARCHAR(60) NULL DEFAULT NULL ,
  `recurrencepresetid` CHAR(8) NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'A P D',
  `lastcommissiondate` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`recurringcommissionid`)  ,
  INDEX `IDX_qu_pap_recurringcommissions_1` (`transid` ASC)  ,
  INDEX `IDX_qu_pap_recurringcommissions_2` (`recurrencepresetid` ASC)  ,
  INDEX `IDX_qu_pap_recurringcommissions_3` (`commtypeid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_recurringcommissionentries`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_recurringcommissionentries` (
  `recurringcommissionentryid` CHAR(8) NOT NULL ,
  `recurringcommissionid` CHAR(8) NULL DEFAULT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `tier` INT NULL DEFAULT NULL ,
  `commission` FLOAT NULL DEFAULT NULL ,
  PRIMARY KEY (`recurringcommissionentryid`)  ,
  INDEX `IDX_qu_pap_recurringcommissionentries_1` (`recurringcommissionid` ASC)  ,
  INDEX `IDX_qu_pap_recurringcommissionentries_2` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_campaignattributes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_campaignattributes` (
  `attributeid` CHAR(8) NOT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`attributeid`)  ,
  INDEX `qu_pap_campaigns_qu_pap_campaignattributes` (`campaignid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_commissiontypeattributes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_commissiontypeattributes` (
  `attributeid` CHAR(8) NOT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `commissiongroupid` CHAR(8) NULL DEFAULT NULL ,
  `name` VARCHAR(40) NULL DEFAULT NULL ,
  `value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`attributeid`)  ,
  INDEX `qu_pap_commissiontypes_qu_pap_commissiontypeattributes` (`commtypeid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_affiliatetrackingcodes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_affiliatetrackingcodes` (
  `affiliatetrackingcodeid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `code` LONGTEXT NULL DEFAULT NULL ,
  `note` LONGTEXT NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `execution` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`affiliatetrackingcodeid`)  ,
  INDEX `qu_pap_commissiontypes_qu_pap_affiliatetrackingcodes` (`commtypeid` ASC)  ,
  INDEX `qu_pap_users_qu_pap_affiliatetrackingcodes` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visits0`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visits0` (
  `visitid` INT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'U' ,
  `visitorid` CHAR(32) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `url` TEXT NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `get_params` TEXT NULL DEFAULT NULL ,
  `anchor` TEXT NULL DEFAULT NULL ,
  `sale` TEXT NULL DEFAULT NULL ,
  `cookies` TEXT NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `useragent` TEXT NULL DEFAULT NULL ,
  `trackmethod` CHAR(1) NULL DEFAULT NULL ,
  `visitoridhash` TINYINT UNSIGNED NOT NULL COMMENT 'control hash of visitorid',
  PRIMARY KEY (`visitid`)  ,
  INDEX `visitorid_hash` (`visitoridhash` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visitors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visitors` (
  `visitorid` CHAR(32) NOT NULL ,
  `name` VARCHAR(100) NULL DEFAULT NULL ,
  `email` VARCHAR(60) NULL DEFAULT NULL ,
  PRIMARY KEY (`visitorid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visitoraffiliates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visitoraffiliates` (
  `visitoraffiliateid` INT NOT NULL AUTO_INCREMENT ,
  `visitorid` CHAR(32) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `channelid` VARCHAR(10) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `browser` CHAR(6) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `validto` DATETIME NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`visitoraffiliateid`)  ,
  INDEX `qu_pap_users_qu_pap_visitoraffiliates` (`userid` ASC)  ,
  INDEX `qu_pap_banners_qu_pap_visitoraffiliates` (`bannerid` ASC)  ,
  INDEX `qu_pap_campaigns_qu_pap_visitoraffiliates` (`campaignid` ASC)  ,
  INDEX `fk_qu_pap_visitoraffiliates_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_visitoraffiliates_ip` (`ip` ASC)  ,
  INDEX `IDX_qu_pap_visitoraffiliates_validto` (`validto` ASC)  ,
  INDEX `qu_pap_visitors_qu_pap_visitoraffiliates` (`visitorid` ASC, `datevisit` ASC)  ,
  INDEX `IDX_qu_pap_visitoraffiliates_browser` (`browser` ASC)  ,
  INDEX `IDX_qu_pap_visitoraffiliates_rtype` (`rtype` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_impressions0`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_impressions0` (
  `impressionid` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'U' ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `userid` VARCHAR(128) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `impressionhash` TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`impressionid`)  ,
  INDEX `impression_hash` (`impressionhash` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_impressions1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_impressions1` (
  `impressionid` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'U' ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `userid` VARCHAR(128) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `impressionhash` TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`impressionid`)  ,
  INDEX `impression_hash` (`impressionhash` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_impressions2`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_impressions2` (
  `impressionid` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'U' ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `userid` VARCHAR(128) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  `channel` VARCHAR(10) NULL DEFAULT NULL ,
  `data1` VARCHAR(255) NULL DEFAULT NULL ,
  `data2` VARCHAR(255) NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `impressionhash` TINYINT UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`impressionid`)  ,
  INDEX `impression_hash` (`impressionhash` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visits1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visits1` (
  `visitid` INT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'U' ,
  `visitorid` CHAR(32) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `url` TEXT NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `get_params` TEXT NULL DEFAULT NULL ,
  `anchor` TEXT NULL DEFAULT NULL ,
  `sale` TEXT NULL DEFAULT NULL ,
  `cookies` TEXT NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `useragent` TEXT NULL DEFAULT NULL ,
  `trackmethod` CHAR(1) NULL DEFAULT NULL ,
  `visitoridhash` TINYINT UNSIGNED NOT NULL COMMENT 'control hash of visitorid',
  PRIMARY KEY (`visitid`)  ,
  INDEX `visitorid_hash` (`visitoridhash` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visits2`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visits2` (
  `visitid` INT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'U' ,
  `visitorid` CHAR(32) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `url` TEXT NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `get_params` TEXT NULL DEFAULT NULL ,
  `anchor` TEXT NULL DEFAULT NULL ,
  `sale` TEXT NULL DEFAULT NULL ,
  `cookies` TEXT NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `useragent` TEXT NULL DEFAULT NULL ,
  `trackmethod` CHAR(1) NULL DEFAULT NULL ,
  `visitoridhash` TINYINT UNSIGNED NOT NULL COMMENT 'control hash of visitorid',
  PRIMARY KEY (`visitid`)  ,
  INDEX `visitorid_hash` (`visitoridhash` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_invoices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_invoices` (
  `invoiceid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `datecreated` DATETIME NULL DEFAULT NULL ,
  `duedate` DATETIME NULL DEFAULT NULL ,
  `datefrom` DATETIME NULL DEFAULT NULL ,
  `dateto` DATETIME NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'U - unpaid, P - paid',
  `number` VARCHAR(40) NULL DEFAULT NULL ,
  `amount` FLOAT NULL DEFAULT NULL ,
  `merchantnote` LONGTEXT NULL DEFAULT NULL ,
  `systemnote` LONGTEXT NULL DEFAULT NULL ,
  `proformatext` LONGTEXT NULL DEFAULT NULL ,
  `invoicetext` LONGTEXT NULL DEFAULT NULL ,
  `datepaid` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`invoiceid`)  ,
  INDEX `fk_qu_pap_invoices_qu_g_accounts1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_accountings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_accountings` (
  `accountingid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `invoiceid` CHAR(8) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `amount` FLOAT NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL COMMENT 'F - fee, C - commissions',
  PRIMARY KEY (`accountingid`)  ,
  INDEX `fk_qu_pap_accounting_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `fk_qu_pap_accountings_qu_pap_invoices1` (`invoiceid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_cachedbanners`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_cachedbanners` (
  `cachedbannerid` INT NOT NULL AUTO_INCREMENT ,
  `userid` CHAR(128) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  `channel` CHAR(10) NULL DEFAULT NULL ,
  `data1` TEXT NULL DEFAULT NULL ,
  `data2` TEXT NULL DEFAULT NULL ,
  `wrapper` VARCHAR(8) NULL DEFAULT NULL ,
  `headers` LONGTEXT NULL DEFAULT NULL ,
  `code` LONGTEXT NULL DEFAULT NULL ,
  `rank` FLOAT NULL DEFAULT 100 ,
  `valid_from` DATETIME NULL DEFAULT NULL ,
  `valid_until` DATETIME NULL DEFAULT NULL ,
  `dynamiclink` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`cachedbannerid`)  ,
  INDEX `IDX_cachedbanners` (`bannerid` ASC, `userid` ASC, `wrapper` ASC, `channel` ASC, `parentbannerid` ASC)  ,
  INDEX `IDX_cachedbannersuserid` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_campaignscategories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_campaignscategories` (
  `categoryid` INT NOT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `image` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`categoryid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_campaignincategory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_campaignincategory` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `categoryid` INT NOT NULL ,
  `campaignid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`id`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_bannerscategories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_bannerscategories` (
  `categoryid` INT NOT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `image` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`categoryid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_bannersincategory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_bannersincategory` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `categoryid` INT NOT NULL ,
  `bannerid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`id`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_campaignrules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_campaignrules` (
  `ruleid` CHAR(8) NOT NULL ,
  `campaignid` CHAR(8) NOT NULL ,
  `stats_what` VARCHAR(1) NULL DEFAULT NULL COMMENT 'S - number of sales, C - value of commissions, T- value of total cost, D - datetime condition',
  `status` VARCHAR(1) NULL DEFAULT NULL COMMENT 'A - Approved, P - Pending, D - Declined, O - Approved or Pending',
  `stats_date` VARCHAR(4) NULL DEFAULT NULL COMMENT 'AM - Actual month, AY - Actual year, AUC - All unpaid commissions, LW - Last week, LTW - Last two weeks, LM - Last month, AT - All time, SD - Since day of last month, , L7D - Last 7 days, L30D - Last 30 days',
  `stats_since` INT NULL DEFAULT NULL ,
  `equation` VARCHAR(1) NULL DEFAULT NULL COMMENT 'L - lower than, H - higher than, B - between, E - equal to',
  `equationvalue1` FLOAT NULL DEFAULT NULL ,
  `equationvalue2` FLOAT NULL DEFAULT NULL ,
  `time_type` VARCHAR(1) NULL DEFAULT NULL COMMENT 'D - daily, M - monthly, W - weekly, T - specific time',
  `time_day` VARCHAR(2) NULL DEFAULT NULL ,
  `time_date` DATE NULL DEFAULT NULL ,
  `time_hour` VARCHAR(2) NULL DEFAULT NULL ,
  `status_to` VARCHAR(1) NOT NULL COMMENT 'change campaign status to: A - active, S - stopped/disabled, invisible to affiliates, W - stopped',
  PRIMARY KEY (`ruleid`)  ,
  INDEX `IDX_qu_pap_rules_1` (`campaignid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_transactions_stats_aff`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_transactions_stats_aff` (
  `transstatid` BIGINT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `payoutstatus` CHAR(1) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `tier` TINYINT NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `channel` VARCHAR(8) NULL DEFAULT NULL ,
  `commission` DOUBLE NULL DEFAULT NULL ,
  `totalcost` DOUBLE NULL DEFAULT NULL ,
  `fixedcost` FLOAT NULL DEFAULT NULL ,
  `commissioncount` INT NULL DEFAULT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`transstatid`)  ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_users1` (`userid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_banners1` (`bannerid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_campaigns1` (`campaignid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_aff_qu_pap_channels1` (`channel` ASC)  ,
  INDEX `IDX_qu_pap_transactions_stats_aff_rtype` (`rtype` ASC)  ,
  INDEX `IDX_qu_pap_transactions_stats_aff_dateinserted` (`dateinserted` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_transstatsdays`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_transstatsdays` (
  `dateinserted` DATE NOT NULL ,
  PRIMARY KEY (`dateinserted`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_transactions_stats`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_transactions_stats` (
  `transstatid` BIGINT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `bannerid` CHAR(8) NULL DEFAULT NULL ,
  `campaignid` CHAR(8) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `payoutstatus` CHAR(1) NULL DEFAULT NULL ,
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `tier` TINYINT NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `channel` VARCHAR(8) NULL DEFAULT NULL ,
  `commission` DOUBLE NULL DEFAULT NULL ,
  `totalcost` DOUBLE NULL DEFAULT NULL ,
  `fixedcost` FLOAT NULL DEFAULT NULL ,
  `commissioncount` INT NULL DEFAULT NULL ,
  `parentbannerid` CHAR(8) NULL DEFAULT NULL ,
  PRIMARY KEY (`transstatid`)  ,
  INDEX `fk_qu_pap_transactions_stats_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_qu_pap_banners1` (`bannerid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_qu_pap_campaigns1` (`campaignid` ASC)  ,
  INDEX `fk_qu_pap_transactions_stats_qu_pap_channels1` (`channel` ASC)  ,
  INDEX `IDX_qu_pap_transactions_stats_rtype` (`rtype` ASC)  ,
  INDEX `IDX_qu_pap_transactions_stats_dateinserted` (`dateinserted` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_keywords`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_keywords` (
  `keywordid` CHAR(8) NOT NULL ,
  `keyword_text` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`keywordid`)  ,
  UNIQUE INDEX `keyword_text_UNIQUE` (`keyword_text` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_keywordstats`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_keywordstats` (
  `keywordstatid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `keywordid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NOT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `dateinserted` DATETIME NOT NULL ,
  `commtypeid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `sales` INT UNSIGNED NULL DEFAULT 0 ,
  `totalcost` FLOAT NULL DEFAULT 0 ,
  `commissions` FLOAT NULL DEFAULT NULL ,
  PRIMARY KEY (`keywordstatid`)  ,
  INDEX `fk_qu_pap_keywordstats_qu_pap_keywords1` (`keywordid` ASC)  ,
  INDEX `fk_qu_pap_keywordstats_qu_pap_users1` (`userid` ASC)  ,
  INDEX `fk_qu_pap_keywordstats_qu_g_accounts1` (`accountid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_keywordclicks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_keywordclicks` (
  `keywordclickid` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `userid` CHAR(8) NOT NULL ,
  `keywordid` CHAR(8) NOT NULL ,
  `clicks` INT UNSIGNED NULL DEFAULT 0 ,
  `dateinserted` DATETIME NOT NULL ,
  PRIMARY KEY (`keywordclickid`)  ,
  INDEX `fk_qu_pap_keywordclicks_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `fk_qu_pap_keywordclicks_qu_pap_users1` (`userid` ASC)  ,
  INDEX `fk_qu_pap_keywordclicks_qu_pap_keywords1` (`keywordid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_news`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_news` (
  `newsid` CHAR(8) NOT NULL ,
  `rtype` CHAR(1) NOT NULL ,
  `dateinserted` DATETIME NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NOT NULL ,
  PRIMARY KEY (`newsid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_userrecipients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_userrecipients` (
  `userrecipientid` INT NOT NULL AUTO_INCREMENT ,
  `userid` CHAR(8) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`userrecipientid`)  ,
  INDEX `fk_qu_pap_userrecipients_qu_pap_users1` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visitornonrefclicks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visitornonrefclicks` (
  `visitornonrefclickid` INT NOT NULL AUTO_INCREMENT ,
  `visitorid` CHAR(32) NOT NULL ,
  `ip` VARCHAR(39) NULL DEFAULT NULL ,
  `browser` CHAR(6) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `scripturl` TEXT NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  PRIMARY KEY (`visitornonrefclickid`)  ,
  INDEX `fk_qu_pap_visitornonrefclicks_qu_g_accounts1` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_visitornonrefclicks_ip` (`ip` ASC)  ,
  INDEX `IDX_qu_pap_visitornonrefclicks_visitorid` (`visitorid` ASC, `datevisit` ASC)  ,
  INDEX `IDX_qu_pap_visitornonrefclicks_browser` (`browser` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_useragents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_useragents` (
  `useragentid` CHAR(6) NOT NULL ,
  `useragent` TEXT NOT NULL ,
  PRIMARY KEY (`useragentid`)  ,
  INDEX `user_agent_index` (`useragent`(150) ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_cachedpages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_cachedpages` (
  `cachedpageid` INT NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(255) NOT NULL ,
  `header` TEXT NULL DEFAULT NULL ,
  `content` LONGBLOB NULL DEFAULT NULL ,
  `valid_until` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`cachedpageid`)  ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_transactions_stats_referrers_urls`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_transactions_stats_referrers_urls` (
  `transstatid` BIGINT NOT NULL AUTO_INCREMENT ,
  `referrerurl` TEXT NOT NULL ,
  `userid` CHAR(8) NULL DEFAULT NULL ,
  `accountid` CHAR(8) NOT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT NULL COMMENT 'A - approved P - pending D - declined ',
  `countrycode` VARCHAR(2) NULL DEFAULT NULL ,
  `commission` DOUBLE NULL DEFAULT NULL ,
  `totalcost` DOUBLE NULL DEFAULT NULL ,
  `commissioncount` INT(11) NULL DEFAULT NULL ,
  `dateinserted` DATETIME NULL DEFAULT NULL ,
  `rtype` CHAR(1) NULL DEFAULT NULL ,
  `commtypeid` CHAR(8) NULL DEFAULT NULL ,
  `grouptype` CHAR(1) NULL DEFAULT NULL COMMENT 'F - first click url, L - last click url',
  PRIMARY KEY (`transstatid`)  ,
  INDEX `fk_qu_pap_top_referrers_urls_qu_g_accounts` (`accountid` ASC)  ,
  INDEX `IDX_qu_pap_top_referrers_urls_rtype` (`rtype` ASC)  ,
  INDEX `IDX_qu_pap_top_referrers_urls_dateinserted` (`dateinserted` ASC)  ,
  INDEX `IDX_qu_pap_top_referrers_urls_referrerurl` (`referrerurl`(150) ASC)  ,
  INDEX `fk_qu_pap_top_referrers_urls_qu_g_userid` (`userid` ASC)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_transstatsreferrerdays`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_transstatsreferrerdays` (
  `dateinserted` DATE NOT NULL ,
  PRIMARY KEY (`dateinserted`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `qu_pap_visits_sales_logs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `qu_pap_visits_sales_logs` (
  `visitid` INT NOT NULL AUTO_INCREMENT ,
  `accountid` CHAR(8) NOT NULL ,
  `visitorid` CHAR(32) NULL DEFAULT NULL ,
  `datevisit` DATETIME NULL DEFAULT NULL ,
  `url` TEXT NULL DEFAULT NULL ,
  `referrerurl` TEXT NULL DEFAULT NULL ,
  `get_params` TEXT NULL DEFAULT NULL ,
  `anchor` TEXT NULL DEFAULT NULL ,
  `sale` TEXT NULL DEFAULT NULL ,
  `ip` CHAR(39) NULL DEFAULT NULL ,
  `useragent` TEXT NULL DEFAULT NULL ,
  `trackmethod` CHAR(1) NULL DEFAULT NULL ,
  `rstatus` CHAR(1) NULL DEFAULT 'I' COMMENT 'I - in processing, U - unprocessed, P - processed',
  `message` TEXT NOT NULL ,
  `retrynumber` INT NOT NULL DEFAULT 0 ,
  `lastretry` DATETIME NULL DEFAULT NULL ,
  `loggroupid` VARCHAR(16) NULL DEFAULT NULL ,
  PRIMARY KEY (`visitid`)  )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
