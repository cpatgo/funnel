INSERT INTO `qu_g_filters` (`filterid`,`name`,`filtertype`,`userid`,`preset`) VALUES ('log_sale', '##Sale debug##', 'tr_list', NULL , 'Y');

INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('level', 'log_sale', 'default', 'level', 'IN', '10,20,30,40,50');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field0', 'log_sale', 'custom', 'groupid', 'L', 'A-');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field1', 'log_sale', 'custom', 'message', 'NL', 'Backward');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field2', 'log_sale', 'custom', 'message', 'NL', 'Not old');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field3', 'log_sale', 'custom', 'message', 'NL', 'Not new');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field4', 'log_sale', 'custom', 'message', 'NL', 'Set AccountId: default1');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field5', 'log_sale', 'custom', 'message', 'NL', 'Before visit processing');
INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('field6', 'log_sale', 'custom', 'message', 'NL', 'Account from visit with accountid= is not valid');