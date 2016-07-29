INSERT INTO `qu_g_filters` (`filterid`,`name`,`filtertype`,`userid`,`preset`) VALUES ('aic_pend', '##Pending affiliates##', 'Affiliates-Group', NULL , 'Y');

INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('rstatus', 'aic_pend', 'default', 'rstatus', 'IN', 'P');


INSERT INTO `qu_g_filters` (`filterid`,`name`,`filtertype`,`userid`,`preset`) VALUES ('t_ntrans', '##Not translated##', 'lt_list', NULL , 'Y');

INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('status', 't_ntrans', 'default', 'status', 'IN', 'N');


INSERT INTO `qu_g_filters` (`filterid`,`name`,`filtertype`,`userid`,`preset`) VALUES ('t_trans', '##Translated##', 'lt_list', NULL , 'Y');

INSERT INTO `qu_g_filter_conditions` (`fieldid`,`filterid`,`sectioncode`,`code`,`operator`,`value`) VALUES ('status', 't_trans', 'default', 'status', 'IN', 'T');